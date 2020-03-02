<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Dispatch;
use App\IMEI;
use App\Inventory;
use App\Lot;
use App\Returns;
use App\Testing;
use App\WarehouseInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("America/New_York");
    }
    public function getLotByLotId(Request $request, $lot_id){


        $lot = Lot::where('lot_id','=', $lot_id)->select('brand_id')->distinct()->get(['brand_id']);
        $brand = array();
        foreach ($lot as $item => $val) {
            array_push($brand, $val->brand->name);
        }
//        print_r($lot);die;
        return $data = [
            'brand' => $brand,
        ];

        /*$data = Lot::where('lot_id','=', $lot_id)->select('color')->groupBy('color')->get();

        $lot = Lot::where('lot_id','=', $lot_id)->get();
        foreach ($lot as $key => $value){
            echo $value->id;
        }
        print_r($lot[0]);die;

        return $data = [
            'lot' => $data,
            'model' => $lot->model,
            'brand' => $lot->brand->name,
            'network' => $lot->network->name,
        ];*/
    }
    public function getLotByLotIdBrand(Request $request, $lot_id){
        $brand_id = Brand::where('name','=',$request->get('lot_brand'))->first();
        $mo = Lot::where('lot_id','=', $lot_id)->
        where('brand_id','=',$brand_id->id)->select('model')->distinct()->get(['model']);
        $models = array();
        foreach ($mo as $item => $val) {
            array_push($models, $val['model']);
        }
//        print_r($lot);die;
        return $data = [
            'model' => $models,
        ];
    }

    public function checkIMEIMatchWithTrackingId(Request $request, $track_id){
        $match = DB::select(DB::raw('select * from inventories where imei = :imei'), ['imei' => $track_id]);
        if (!empty($match) || $match != null){
            return 'false';
        } else {
            return 'true';
        }
    }

    public function getLotByLotIdModel(Request $request, $lot_id){
        $brand_id = Brand::where('name','=',$request->get('lot_brand'))->first();
        $mo = Lot::where('lot_id','=', $lot_id)->join('networks','networks.id','=','lots.network_id')->
                    where('brand_id','=',$brand_id->id)->
                    where('model','=',$request->get('lot_model'))->select('networks.name')->first();
        $color = Lot::where('lot_id','=', $lot_id)->where('brand_id','=',$brand_id->id)->
                        where('model','=',$request->get('lot_model'))->select('color')->groupBy('color')->get();
        $c = array();
        foreach ($color as $item => $value) {
            array_push($c,$value['color']);
        }
        return $data = [
            'network' => $mo['name'],
            'color' => $c,
        ];
    }
    public function getStorageByColor(Request $request, $lot_id){
        $brand_id = Brand::where('name','=',$request->get('brand'))->first();
        $storage = Lot::join('storages','lots.storage_id','=','storages.id')
            ->where('lot_id','=', $lot_id)
            ->where('color','=', $request->get('color'))
            ->select('storages.*')->groupBy('storage_id')->get();

        $networks = Lot::join('networks','lots.network_id','=','networks.id')
            ->where('lot_id','=', $lot_id)
            ->where('color','=', $request->get('color'))
            ->where('brand_id', '=', $brand_id['id'])
            ->where('model','=', $request->get('model'))
            ->select('networks.*')->groupBy('network_id')->get();
        $net = array();
        foreach ($networks as $k => $v) {
            array_push($net, $v->name);
        }
        if (count($net) < 2) {
            return $data = ['storage' => $storage, 'network' => $net];
        } else {
            return $data = ['storage' => $storage, 'networks' => $net];
        }
    }
    public function getAsinByStorage(Request $request, $lot_id){
        $brand_id = Brand::where('name','=',$request->get('lot_brand'))->first();
        return $data = Lot::where('lot_id','=', $lot_id)
            ->where('color','=', $request->get('color'))
            ->where('storage_id','=', $request->get('storage_id'))
            ->where('brand_id','=', $brand_id->id)->get();
    }
    public function getAsinByStorageRest(Request $request, $lot_id){

        $brand_id = Brand::where('name','=',$request->get('lot_brand'))->first();
        $data = DB::select(DB::raw('select asin from lots where color = :color AND storage_id = :storage AND brand_id = :brand AND lot_id = :lot')
                                    ,['color' => $request->get('color'), 'storage' => $request->get('storage_id'), 'brand' => $brand_id->id, 'lot' => $lot_id]);

        return $data = ['asin' => $data];

    }
    public function getAsinByStorageQty(Request $request, $lot_id){

        $brand_id = Brand::where('name','=',$request->get('brand'))->first();
        $data = DB::select(DB::raw('select asin_total_quantity, inventory_quantity from lots where color = :color AND storage_id = :storage AND brand_id = :brand AND lot_id = :lot
                                    AND asin = :asin AND model = :model'),['color' => $request->get('color'), 'storage' => $request->get('storage_id'),
                                    'brand' => $brand_id->id, 'lot' => $lot_id,'asin' => $request->get('asin'), 'model' => $request->get('model')]);

        return $data = ['storage' => $data];

    }
    public function getAsinQuantityByAsin(Request $request, $lot_id){
        return $data = Lot::where('lot_id','=', $lot_id)
            ->where('color','=', $request->get('color'))
            ->where('storage_id','=', $request->get('storage_id'))
            ->where('asin','=', $request->get('asin'))->first();
    }
    public function getModelsByBrand(Request $request, $brand_id){
        $data = DB::select(DB::raw('SELECT name from models where brand_id = :brand'),['brand' => $brand_id]);
        return $data = ['model' => $data, ];
    }
    public function getLotByImeiTest(Request $request, $imei){

        $inventory = Inventory::join('warehouse_in_out','inventories.id','=','warehouse_in_out.inventory_id')
            ->select('inventories.*')
            ->where('imei','=', $imei)->first();
        // $x = WarehouseInOut::where('Account',auth()->user()->name)->where('acc_status','!=','1')->count();
        // if($x == 0){
        //     return 'not found in history';
        // }
        if ($inventory) {
            $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
            if ($has_in_testing) {
                return $data = [
                    'testing_id' => $has_in_testing->inventory_id,
                ];
            } else {
                $data = [
                    'brand' => $inventory->lot->brand->name,
                    'model' => $inventory->lot->model,
                    'network' => $inventory->lot->network->name,
                    'color' => $inventory->lot->color,
                    'storage' => $inventory->lot->storage->name,
                    'category' => $inventory->category->name,
                ];
                $return = Returns::where('inventory_id','=', $inventory->id)->orderByDesc('id')->first();
                if ($return){
                    $data['return_message'] = $return->message;
                    $data['return_date'] = $return->created_at;
                }
                return $data;
            }
        }
        else{
            return 123456789;
        }
    }

    public function getLotByImei(Request $request, $imei){
        $inventory = Inventory::join('warehouse_in_out','inventories.id','=','warehouse_in_out.inventory_id')
            ->select('inventories.*')
            ->where('imei','=', $imei)->first();
        // $x = WarehouseInOut::where('Account',auth()->user()->name)->where('acc_status','!=','1')->count();
        // if($x == 0){
        //     return 'not found in history';
        // }
        if ($inventory) {
            $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
            if ($has_in_testing) {
                return $data = [
                    'testing_id' => $has_in_testing->id,
                ];
            } else {
                $data = [
                    'brand' => $inventory->lot->brand->name,
                    'model' => $inventory->lot->model,
                    'network' => $inventory->lot->network->name,
                    'color' => $inventory->lot->color,
                    'storage' => $inventory->lot->storage->name,
                    'category' => $inventory->category->name,
                ];
                $return = Returns::where('inventory_id','=', $inventory->id)->orderByDesc('id')->first();
                if ($return){
                    $data['return_message'] = $return->message;
                    $data['return_date'] = $return->created_at;
                }
                return $data;
            }
        }
        else{
            return 123456789;
        }
    }


    public function getLotByImeiForDispatch(Request $request, $imei) {
        $inventory = Inventory::where('imei','=', $imei)->first();
        if ($inventory) {
            $already_exist_in =WarehouseInOut::where('inventory_id','=',$inventory->id)->first();
            if (!$already_exist_in){
                return $data = [
                    'not_released' => true,
                ];
            }
            $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
//            $has_in_testing = true;

            if ($has_in_testing) {
                $has_dispatch = Dispatch::where('inventory_id', '=', $inventory->id)
                    ->where('status', '=', 0)
                    ->first();
                if ($has_dispatch) {
                    return $data = [
                        'already_dispatched' => true,
                    ];
                } else {

                    return $data = [
                        'brand' => $inventory->lot->brand->name,
                        'model' => $inventory->lot->model,
                        'network' => $inventory->lot->network->name,
                        'color' => $inventory->lot->color,
                        'storage' => $inventory->lot->storage->name,
                        'category' => $inventory->category->name,
                    ];
                }
            }
            else {
                $is_new = Category::find($inventory->category_id);
                if ($is_new->id == 13){
                    $has_dispatch = Dispatch::where('inventory_id', '=', $inventory->id)
                        ->where('status', '=', 0)
                        ->first();
                    if ($has_dispatch) {
                        return $data = [
                            'already_dispatched' => true,
                        ];
                    } else {

                        return $data = [
                            'brand' => $inventory->lot->brand->name,
                            'model' => $inventory->lot->model,
                            'network' => $inventory->lot->network->name,
                            'color' => $inventory->lot->color,
                            'storage' => $inventory->lot->storage->name,
                            'category' => $inventory->category->name,
                        ];
                    }
                }
                else{
                    return $data = [
                        'not_tested' => true,
                    ];
                }
            }
        }
        else{
            return $data = [
                'not_found' => true,
            ];
        }
    }
    public function getModelByBrand(Request $request){
        $brand_id = $request->get('brand_id');
        return $models = Lot::where('brand_id','=', $brand_id)
            ->groupBy('model')
            ->select('model')
            ->get();
    }
    public function getLotByBrandPlusModel(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        return $models = Lot::where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('lot_id')
            ->select('lot_id')
            ->get();
    }
    public function network_storage_color_cat_by_brand(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');

        $networks = Lot::join('inventories','lots.id','=','inventories.lots_primary_key')
            ->join('networks','lots.network_id','=','networks.id')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('network_id')
            ->select('networks.*')
            ->get();
        $storage = Lot::join('inventories','lots.id','=','inventories.lots_primary_key')
            ->join('storages','lots.storage_id','=','storages.id')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('storage_id')
            ->select('storages.*')

            ->get();
        $colors = Lot::join('inventories','lots.id','=','inventories.lots_primary_key')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('color')
            ->get();
        $categories = Lot::join('inventories','lots.id','=','inventories.lots_primary_key')
            ->join('categories','inventories.category_id','=','categories.id')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('category_id')
            ->select('categories.*')

            ->get();
        return $response=
            [
                'storage'=>$storage,
                'networks'=>$networks,
                'colors'=>$colors,
                'categories'=>$categories,
            ];
    }

    public function getChangeCategory(Request $request){
        $imei = $request->get('imei');
        $category_id = $request->get('category_id');
        return $models = Inventory::where('imei','=', $imei)
            ->update([
                'category_id' => $category_id
            ]);
    }

    public function inventoryDeleteByImei(Request $request){
        $imei = $request->get('imei');
        $inventory = Inventory::where('imei','=', $imei)->first();

        $result = Inventory::where('imei','=', $imei)->delete();

        $lotRecord = Lot::where('id', '=', $inventory->lots_primary_key)->first();

        $inventory_quantity = $lotRecord->inventory_quantity - 1;
        $result = Lot::where('id', '=', $lotRecord->id)
            ->update([
                'inventory_quantity' => $inventory_quantity,
            ]);

        return $inventory_quantity;
    }
    public function updateBoughtQty(Request $request, $lot_id){
        $bought_qty = $request->get('bought_qty');
        $received_qty = $request->get('received_qty');

        $result = Lot::where('lot_id', '=', $lot_id)
            ->update([
                'bought_quantity' => $bought_qty,
                'received_quantity' => $received_qty,
            ]);
        return 1;
    }
    public function revert_dispatch_by_imei(Request $request){
        $imei = $request->get('imei');

        $inventory = Inventory::where('imei','=',$imei)->first();
        if ($inventory) {
            $res = Inventory::where('id', '=', $inventory->id)
                ->update([
                    'status' => 1,
                ]);

            $result = Dispatch::where('inventory_id', '=', $inventory->id)->delete();
        }
        else{
            $imei = '0'.$imei;
            $inventory = Inventory::where('imei','=',$imei)->first();
            if ($inventory)
                $res = Inventory::where('id','=',$inventory->id)
                    ->update([
                        'status' => 1,
                    ]);

            $result = Dispatch::where('inventory_id','=', $inventory->id)->delete();
        }
        return 1;
    }

    public function updateAsinQuantity(Request $request, $lot_id){
        $lot = Lot::where('lot_id','=', $lot_id)
            ->where('color','=', $request->get('color'))
            ->where('storage_id','=', $request->get('storage_id'))->first();
        if ($lot){
            $qty_for_update = $request->get('updated_quantity');
            $updated_asin_qty = $lot->asin_total_quantity + $qty_for_update;
            $updated_bought_qty = $lot->bought_quantity + $qty_for_update;
            $data = Lot::where('id','=', $lot->id)
                ->update([
                    'asin_total_quantity' => $updated_asin_qty,
                ]);

            $data = Lot::where('lot_id','=', $lot->lot_id)
                ->update([
                    'bought_quantity' => $updated_bought_qty,
                    'received_quantity' => $updated_bought_qty,
                ]);
            return $data = $updated_asin_qty - $lot->inventory_quantity;
        }
    }

    public function color_by_brand_plus_model(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        return $models = Lot::where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->groupBy('color')
            ->select('color')
            ->get();
    }
    public function storage_by_brand_plus_model_color(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        $color = $request->get('color');
        return $models = Lot::join('storages','lots.storage_id','=','storages.id')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->where('color','=', $color)
            ->groupBy('storage_id')
            ->select('storages.id as storage_id','storages.name as storage_name')
            ->get();
    }
    public function lot_by_brand_plus_model_color_storage(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        $color = $request->get('color');
        $storage_id = $request->get('storage_id');
        return $models = Lot::where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->where('color','=', $color)
            ->where('storage_id','=', $storage_id)
            ->groupBy('lot_id')
            ->select('lot_id')
            ->get();
    }

    public function lot_asin_by_brand_plus_model_color_storage(Request $request){
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        $color = $request->get('color');
        $storage_id = $request->get('storage_id');
        $lot_id = $request->get('lot_id');
        return $models = Lot::join('networks','lots.network_id','=','networks.id')
            ->where('brand_id','=', $brand_id)
            ->where('model','=', $model)
            ->where('color','=', $color)
            ->where('storage_id','=', $storage_id)
            ->where('lot_id','=', $lot_id)
            ->groupBy('asin')
            ->select('asin','networks.name as network')
            ->get();
    }

    public function get_asin_by__(Request $request){
        $brand_id = $request->get('brand_id');
        $network_id = $request->get('network_id');
        $model = $request->get('model');
        $color = $request->get('color');
        $storage_id = $request->get('storage_id');

        $asins = Lot::join('networks','lots.network_id','=','networks.id')
            ->join('brands','lots.brand_id','=','brands.id')
            ->where('brands.name','=', $brand_id)
            ->where('networks.name','=', $network_id)
            ->where('model','=', $model)
            ->where('color','=', $color)
            ->where('storage_id','=', $storage_id)
            ->groupBy('asin')
            ->select('asin')
            ->get();
        return $asins;
    }

    public function get_imei_category(Request $request){
        $imei = $request->get('imei');
        $inventory = Inventory::where('imei','=',$imei)
            ->where('status','=',0)->first();
        if ($inventory) {
            $dispatch_count = Dispatch::where('inventory_id', '=', $inventory->id)->get()->count();
            $return_data = DB::select(DB::raw('SELECT r.message,r.created_at, u.name, i.category_id from returns r INNER JOIN inventories i on i.id = r.inventory_id
	                                          inner JOIN testings t on t.id = r.testing_id INNER JOIN users u on u.id = r.created_by 
                                              where i.imei = :imei'),['imei' => $imei]);

          /*  $return_data = Returns::where('inventory_id', '=', $inventory->id)->get();
            print_r($return_data);die;*/
            $data = [
                'sold_category' => $inventory->category->name,
                'sold_count' => $dispatch_count,
                'return_data' => $return_data,
            ];
            return $data;
        }
    }
}
