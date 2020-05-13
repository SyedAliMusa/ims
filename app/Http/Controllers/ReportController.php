<?php

namespace App\Http\Controllers;

use App\AttachIMEIToLCD;
use App\Brand;
use App\Dispatch;
use App\Inventory;
use App\LcdInventory;
use App\LcdIssuedTo;
use App\Lot;
use App\Problems;
use App\Returns;
use App\Testing;
use App\Repairing;
use App\WarehouseInOut;

use foo\Foo;
use App\Category;

use App\RepairingProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;
use PhpParser\Node\Scalar\String_;
use stdClass;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("America/New_York");
    }

    public function lot(Request $request)
    {
        $brands = Brand::all();
        $products = [];
        $total_asin_quantity = 0;
        $available_quantity = 0;
        $dispatched_quantity = 0;
        if ($request->isMethod('GET')) {
            return view('customer.reports.lot', compact('brands', 'products', 'total_asin_quantity', 'available_quantity', 'dispatched_quantity', 'storage', 'colors'));
        } elseif ($request->isMethod('POST')) {
//            return $request->all();
            $lot_id = $request->get('lot_id');
            $model = $request->get('model');
            $brand_id = $request->get('brand_id');

            $products = Lot::join('inventories', 'lots.id', '=', 'inventories.lots_primary_key')
                ->where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->get();

            $total_asin_quantity = Lot::where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->select(DB::Raw('sum(asin_total_quantity) as quantity'))
                ->value('quantity');
            $storages = Lot::where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->groupBy('storage_id')
                ->get();
            $colors = Lot::where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->groupBy('color')
                ->get();
            $available_quantity = Lot::join('inventories', 'lots.id', '=', 'inventories.lots_primary_key')
                ->where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->where('status', '=', 1)
                ->select(DB::Raw('count(imei) as quantity'))
                ->value('quantity');

            $dispatched_quantity = Lot::join('inventories', 'lots.id', '=', 'inventories.lots_primary_key')
                ->where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->where('status', '=', 0)
                ->select(DB::Raw('count(imei) as quantity'))
                ->value('quantity');


            // color wise randring
            $data = Lot::where('lot_id', '=', $lot_id)
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->select('color')
                ->groupBy('color')->get();

            foreach ($data as $color){
                $storages = Lot::where('model', '=', $model)
                    ->where('brand_id', '=', $brand_id)
                    ->where('lot_id', '=', $lot_id)
                    ->where('color', '=', $color->color)
                    ->select('storage_id')
                    ->groupBy('storage_id')->get();
                $color->storages = $storages;

                foreach ($color->storages as $storage){
                    $categories = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                        ->where('lot_id', '=', $lot_id)
                        ->where('model', '=', $model)
                        ->where('brand_id', '=', $brand_id)
                        ->where('color', '=', $color->color)
                        ->where('storage_id', '=', $storage->storage_id)
                        ->select('category_id')
                        ->groupBy('category_id')->get();
                    $storage->categories = $categories;

                    foreach ($storage->categories as $category){
                        $category->available = 0;
                        $stock = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                            ->where('lot_id', '=', $lot_id)
                            ->where('model', '=', $model)
                            ->where('brand_id', '=', $brand_id)
                            ->where('color', '=', $color->color)
                            ->where('storage_id', '=', $storage->storage_id)
                            ->where('category_id', '=', $category->category_id)
                            ->select(DB::Raw('count(imei) as stock'))
                            ->groupBy('category_id')->first()->stock;
                        $category->stock = $stock;

                        $available = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                            ->where('lot_id', '=', $lot_id)
                            ->where('model', '=', $model)
                            ->where('brand_id', '=', $brand_id)
                            ->where('color', '=', $color->color)
                            ->where('storage_id', '=', $storage->storage_id)
                            ->where('category_id', '=', $category->category_id)
                            ->where('status', '=', 1)
                            ->select(DB::Raw('count(imei) as available'))
                            ->groupBy('category_id')->first();

                        if ($available){
                            $category->available = $available->available;
                        }
                        else{
                            $category->available = 0;
                        }
                    }
                }
            }
            $color_summary = $data;
            return view('customer.reports.lot', compact('brands', 'products', 'total_asin_quantity', 'available_quantity', 'dispatched_quantity', 'storages', 'colors','color_summary'));
        }
    }

      //////////////Refurbisher reprot //////////////

    public function refurbisherReport(Request $request){
        if ($request->isMethod('GET')) {
            $user = DB::table("users")->where('account_type','refurbishing')->get();
            $data = [];
            $mar = '0';
            $rep = '0';
            $bro = '0';
            return view('customer.reports.refurbisher_report', compact('user','data','rep','mar','bro'));
        }elseif ($request->isMethod('POST')) {
            $user = DB::table("users")->where('account_type','refurbishing')->get();
           $marry = [];
           $data = [];
           $broken = [];

           if($request->date == 'Today'){
               $d = date("Y-m-d",time());

                $rep = DB::table('repairings')
                    ->join('inventories','repairings.inventory_id','inventories.id')
                    ->join('lots','inventories.lots_primary_key','lots.id')
                    ->where('repairings.created_by', $request->name)
                    ->whereDate('repairings.updated_at', '=', $d)
                    ->count();
                 $mar = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->whereDate('attach_imei_to_lcds.updated_at', '=', $d)
                        ->count();
                 $bro =  DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->count();

                if($request->type == "Repared"){
                    $data = DB::table('repairings')
                        ->join('inventories','repairings.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->where('repairings.created_by', $request->name)
                         ->whereDate('repairings.updated_at', '=', $d)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }elseif($request->type == "Marry"){

                    $marry = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('attach_imei_to_lcds.updated_at', '=', $d)
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }else{
                    $broken = DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->get();

                    // $broken =  DB::table('lcd_inventories')
                    //     ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                    //     ->where('lcd_issued_to.assigned_to_account',$request->name)
                    //     ->where('lcd_issued_to.status','4')
                    //     ->whereDate('lcd_inventories.created_at', '=', $d)
                    //     ->get();
                    // dd($broken);
                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }

           }elseif($request->date == '7 Days'){
                $to = date("Y-m-d",time());
                $f = time()-604800;
                $from = date("Y-m-d",$f);

                $rep = DB::table('repairings')
                    ->join('inventories','repairings.inventory_id','inventories.id')
                    ->join('lots','inventories.lots_primary_key','lots.id')
                    ->where('repairings.created_by', $request->name)
                    ->whereDate('repairings.updated_at', '<=', $to)
                    ->whereDate('repairings.updated_at', '>=', $from)
                    ->count();
                 $mar = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->whereDate('attach_imei_to_lcds.updated_at', '<=', $to)
                        ->whereDate('attach_imei_to_lcds.updated_at', '>=', $from)
                        ->count();
                 $bro =   DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_inventories.updated_at', '<=', $to)
                        ->whereDate('lcd_inventories.updated_at', '>=', $from)
                        ->count();

                if($request->type == "Repared"){
                    $data = DB::table('repairings')
                        ->join('inventories','repairings.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->where('repairings.created_by', $request->name)
                        ->whereDate('repairings.updated_at', '<=', $to)
                        ->whereDate('repairings.updated_at', '>=', $from)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }elseif($request->type == "Marry"){

                    $marry = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('attach_imei_to_lcds.updated_at', '<=', $to)
                        ->whereDate('attach_imei_to_lcds.updated_at', '>=', $from)
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }else{
                    $broken =   DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_inventories.updated_at', '<=', $to)
                        ->whereDate('lcd_inventories.updated_at', '>=', $from)
                        ->get();
                    // dd($broken);
                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }

           }else{
            //   dd($request);
                $from = date("Y-m-d",strtotime($request->to));
                // $f = time()-604800;
                $to = date("Y-m-d",strtotime($request->from));

                $rep = DB::table('repairings')
                    ->join('inventories','repairings.inventory_id','inventories.id')
                    ->join('lots','inventories.lots_primary_key','lots.id')
                    ->where('repairings.created_by', $request->name)
                    ->whereDate('repairings.updated_at', '<=', $to)
                    ->whereDate('repairings.updated_at', '>=', $from)
                    ->count();
                 $mar = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->whereDate('attach_imei_to_lcds.updated_at', '<=', $to)
                        ->whereDate('attach_imei_to_lcds.updated_at', '>=', $from)
                        ->count();
                 $bro =   DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_inventories.updated_at', '<=', $to)
                        ->whereDate('lcd_inventories.updated_at', '>=', $from)
                        ->count();

                if($request->type == "Repared"){
                    $data = DB::table('repairings')
                        ->join('inventories','repairings.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->where('repairings.created_by', $request->name)
                        ->whereDate('repairings.updated_at', '<=', $to)
                        ->whereDate('repairings.updated_at', '>=', $from)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }elseif($request->type == "Marry"){

                    $marry = DB::table('attach_imei_to_lcds')
                        ->join('inventories','attach_imei_to_lcds.inventory_id','inventories.id')
                        ->join('lots','inventories.lots_primary_key','lots.id')
                        ->join('lcd_inventories','attach_imei_to_lcds.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('attach_imei_to_lcds.updated_at', '<=', $to)
                        ->whereDate('attach_imei_to_lcds.updated_at', '>=', $from)
                        ->where('attach_imei_to_lcds.created_by', $request->name)
                        ->get();

                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }else{
                    $broken =   DB::table('lcd_inventories')
                        ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->where('lcd_issued_to.assigned_to_account',$request->name)
                        ->where('lcd_issued_to.status','5')
                        ->whereDate('lcd_inventories.updated_at', '<=', $to)
                        ->whereDate('lcd_inventories.updated_at', '>=', $from)
                        ->get();
                    // dd($broken);
                return view('customer.reports.refurbisher_report', compact('data','marry','broken','user','rep','mar','bro'));
                }
           }


        }
    }

    public function getsinglecolor(Request $request)
    {
        if ($request->has('from') && $request->has('to')) {
            if ($request->get('from') != '' && $request->get('to') != '') {
                if ($request->has('colors') && $request->get('colors') != '') {
                    $from = strtotime($request->get('from'));
                    $to = strtotime($request->get('to'));
                    $date_inc = strtotime("+1 day", $to);
                    $to = date("Y-m-d", $date_inc);
                    $from = date("Y-m-d", $from);
                    $color = $request->get('colors');

                    $inprogress = DB::select(DB::raw('SELECT w.color_folder
                            FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                            INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                            INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                            where w.color_folder = :color AND w.created_at BETWEEN :from AND :to AND i.status != 0'), ['from' => $from, 'to' => $to, 'color' => $color]);

                    $dispatched = DB::select(DB::raw('SELECT w.color_folder
                            FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                            INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                            INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                            where w.color_folder = :color AND w.created_at BETWEEN :from AND :to AND i.status = 0'), ['from' => $from, 'to' => $to, 'color' => $color]);


                    $results = DB::select(DB::raw('SELECT w.color_folder, l.color as color, i.status as status, i.imei as imei, w.created_at as c_date
                            FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                            INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                            INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                            where w.color_folder = :color AND w.created_at BETWEEN :from AND :to order by w.id DESC '), ['from' => $from, 'to' => $to, 'color' => $color]);
                    return view('customer.reports.single_color', ['results' => $results, 'inprogress' => $inprogress, 'dispatched' => $dispatched]);
                    //            return view('customer.reports.single_color', compact('results'));
                }
            } else {
                $color = $request->get('colors');

                $inprogress = DB::select(DB::raw('SELECT w.color_folder
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color AND i.status != 0'), ['color' => $color]);

                $dispatched = DB::select(DB::raw('SELECT w.color_folder
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color AND i.status = 0'), ['color' => $color]);


                $results = DB::select(DB::raw('SELECT w.color_folder, l.color as color, i.status as status, i.imei as imei, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color order by w.id DESC '), ['color' => $color]);
                return view('customer.reports.single_color', ['results' => $results, 'inprogress' => $inprogress, 'dispatched' => $dispatched]);
                //            return view('customer.reports.single_color', compact('results'));
            }
        } else {
            $color = $request->get('color');

            $inprogress = DB::select(DB::raw('SELECT w.color_folder
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color AND i.status != 0'), ['color' => $color]);

            $dispatched = DB::select(DB::raw('SELECT w.color_folder
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color AND i.status = 0'), ['color' => $color]);


            $results = DB::select(DB::raw('SELECT w.color_folder, l.color as color, i.status as status, i.imei as imei, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where w.color_folder = :color order by w.id DESC '), ['color' => $color]);
            return view('customer.reports.single_color', ['results' => $results, 'inprogress' => $inprogress, 'dispatched' => $dispatched]);
            //            return view('customer.reports.single_color', compact('results'));
            }
    }

    public function getcolorbase(Request $request){

        if ($request->get('from') != '' && $request->get('to') != '') {
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            if ($request->get('colors') != ''){
                $color = $request->get('colors');
                $results = DB::select(DB::raw('SELECT i.status as inv_status, t.id as testing_id,w.color_folder, l.model as model, s.name
                        as storage,p.status, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to,
                        w.created_at as c_date FROM warehouse_in_out w INNER JOIN inventories i on i.id = w.inventory_id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        LEFT JOIN testings t on t.inventory_id = i.id LEFT JOIN problems p on p.testing_id = t.id where i.status = 1 AND w.created_at
                        BETWEEN :from AND :to  AND w.color_folder = :color GROUP by imei order by w.id DESC '),
                        ['from' => $from, 'to' => $to, 'color' => $color]);
            } else {
                $results = DB::select(DB::raw('SELECT i.status as inv_status, t.id as testing_id,w.color_folder, l.model as model, s.name
                        as storage,p.status, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to,
                        w.created_at as c_date FROM warehouse_in_out w INNER JOIN inventories i on i.id = w.inventory_id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        LEFT JOIN testings t on t.inventory_id = i.id LEFT JOIN problems p on p.testing_id = t.id
                        where i.status = 1 AND w.created_at BETWEEN :from AND :to GROUP BY i.imei
                        order by w.id DESC'), ['from' => $from, 'to' => $to]);
            }
        } else {
            $color = $request->get('colors');
            $results = DB::select(DB::raw('SELECT i.status as inv_status, t.id as testing_id,w.color_folder, l.model as model, s.name
                        as storage,p.status, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to,
                        w.created_at as c_date FROM warehouse_in_out w INNER JOIN inventories i on i.id = w.inventory_id
                        INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        LEFT JOIN testings t on t.inventory_id = i.id LEFT JOIN problems p on p.testing_id = t.id
                        where i.status = 1 AND w.color_folder = :color GROUP by imei order by w.id DESC '), ['color' => $color]);
        }
        return view('customer.reports.color_folder', compact('results'));
    }

    public function getcolors(Request $request){

        if ($request->get('from') != '' && $request->get('to') != '') {
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            if ($request->get('colors') != ''){
                $color = $request->get('colors');
                $results = DB::select(DB::raw('SELECT i.status,w.color_folder, l.model as model, s.name as storage, l.color as color,
                        i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id INNER JOIN lots l on
                        l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by INNER JOIN storages s on
                        s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where
                        w.created_at BETWEEN :from AND :to  AND w.color_folder = lower(:color) /*AND i.status != 0*/ GROUP by imei order by w.id DESC '),
                    ['from' => $from, 'to' => $to, 'color' => $color]);
            } else { //i.status,
                $results = DB::select(DB::raw('SELECT i.status,w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where
                        w.created_at BETWEEN :from AND :to /*AND i.status != 0*/ GROUP by imei order by w.id DESC '), ['from' => $from, 'to' => $to]);
            }
        } elseif ($request->has('imei')) {
            $im = $request->get('imei');
            $results = DB::select(DB::raw('SELECT i.status,w.color_folder, l.model as model, s.name as storage, l.color as color,
                        i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id
                        where i.imei = :im /*AND i.status != 0*/ GROUP by imei order by w.id DESC '), ['im' => $im]);
        } else {
            $color = $request->get('colors');
            $results = DB::select(DB::raw('SELECT i.status,w.color_folder, l.model as model, s.name as storage, l.color as color,
                        i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where
                        w.color_folder = lower(:color) /*AND i.status != 0*/ GROUP by imei order by w.id DESC '), ['color' => $color]);
        }
        return view('customer.reports.color', compact('results'));
    }

    ///////////////refurbisher lcd  Report ////////////

    public function refurbisherLcdReport(Request $request){
        // dd($request);
        if ($request->isMethod('GET')) {
            $user = DB::table("users")->where('account_type','refurbishing')->get();
            $data = [];
            $mar = '0';
            $rep = '0';
            $bro = '0';
            $br = '0';
            $ready = '0';
            $dispatch = '0';
            $udata = "";
            $udata_to = "";
            $udata_from = "";
            $urefurbisherType = "";
            $uname = "";
            $utype = "";
            return view('customer.reports.refurbisher_lcd_report', compact('user','data','rep','mar','bro','br','ready','dispatch','udata','udata_to','udata_from','urefurbisherType','uname','utype'));
        }elseif ($request->isMethod('POST')) {
            $udata = $request->date;
            $udata_to = $request->to;
            $udata_from = $request->from;
            $urefurbisherType = $request->refurbisherType;
            $uname = $request->name;
            $utype = $request->type;

            $user = DB::table("users")->where('account_type','refurbishing')->get();
           $marry = [];
           $data = [];
           $broken = [];
           $bokn = [];
            $bro = '';
            $moved = '';
            $ready = array();
            $dispatch = array();
            $stuckCount = array();
           if($request->date == 'Today'){
               $d = date("Y-m-d",time());
            //   echo date("h:i:sa");
            //   dd($request->date);
               //5 for broken 4 for recive 3 for relesed
                    if($request->refurbisherType == 'LCD_Refurbished'){
                   $br = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                       $br =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }


                 if($request->refurbisherType == 'LCD_Refurbished'){
                   $rep = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', 3)
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                       $rep =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', 3)
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
            //   dd($rep);
                 if($request->refurbisherType == 'LCD_Refurbished'){
                   $b = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')                         ->get();
                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }
                    }else{
                       $b =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')                         ->get();
                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }
                    }
            //   dd($b);
                 if($request->refurbisherType == 'LCD_Refurbished'){
                   $ds = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')                         ->get();
                        foreach($ds as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }
                    }else{
                       $ds =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($ds as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }
                    }
                    // dd($d);
                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();

                        $moved = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', 'Phone_Refurbished')
                        ->count();
                        //dd($data);
                    }else{
                       $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
                    // dd($mar);
                //  $bro =  DB::table('lcd_inventories')
                //         ->join('lcd_issued_to','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                //         ->where('lcd_issued_to.assigned_to_account',$request->name)
                //         ->where('lcd_issued_to.status','4')
                //         ->whereDate('lcd_inventories.created_at', '=', $d)
                //         ->count();

                if($request->refurbisherType == 'LCD_Refurbished'){
                    $bros =   DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }
                }else{
                    $bros =   DB::table('lcd_issued_to')
                    ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                    ->whereDate('lcd_issued_to.updated_at', '=', $d)
                    ->where('lcd_issued_to.status', '3')
                    ->where('lcd_issued_to.assigned_to_account', $request->name)
                    ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                    ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }
                }

                if($request->type == "Broken"){
                    if($request->refurbisherType == 'LCD_Refurbished'){
                    $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }

                }elseif($request->type == "Received"){
                    if($request->refurbisherType == 'LCD_Refurbished'){
                    $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '4')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '=', $d)
                        ->where('lcd_issued_to.status', '4')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }

                }elseif($request->type == "Released"){

                        if($request->refurbisherType == 'LCD_Refurbished'){
                            $marry = DB::table('lcd_issued_to')
                                ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                                ->whereDate('lcd_issued_to.created_at', '=', $d)
                                //->where('lcd_issued_to.status', 3)
                                // ->where('lcd_issued_to.receiver_name', 'Rainel')
                                // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                                ->orderBy('lcd_issued_to.created_at', 'DESC')->get();
                                //dd($data);
                        }else{
                            $marry = DB::table('lcd_issued_to')
                            ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                            ->whereDate('lcd_issued_to.updated_at', '=', $d)
                            //->where('lcd_issued_to.status', 3)
                            ->where('lcd_issued_to.assigned_to_account', $request->name)
                            ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                            ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        }

                        // dd($marry);

                }elseif($request->type == "Stuck"){
                    if($request->refurbisherType == 'LCD_Refurbished'){
                            $broken =   DB::table('lcd_issued_to')
                                ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                                ->whereDate('lcd_issued_to.updated_at', '=', $d)
                                ->where('lcd_issued_to.status', '3')
                                //->where('lcd_issued_to.receiver_name', 'Rainel')
                                ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                                ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                                //dd($data);
                        }else{
                            $broken =   DB::table('lcd_issued_to')
                            ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                            ->whereDate('lcd_issued_to.updated_at', '=', $d)
                            ->where('lcd_issued_to.status', '3')
                            ->where('lcd_issued_to.assigned_to_account', $request->name)
                            ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                            ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        }
                    // dd($broken);

                }

           }elseif($request->date == '7 Days'){
                $to = date("Y-m-d",time());
                $f = time()-604800;
                $from = date("Y-m-d",$f);

                if($request->refurbisherType == 'LCD_Refurbished'){
                   $b = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }
                    }else{
                      $b =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();

                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }

                    }
               // dd($ready);

                if($request->refurbisherType == 'LCD_Refurbished'){
                   $d = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                         foreach($d as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }
                    }else{
                      $d =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                         foreach($d as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }

                    }
               // dd($ready);

                if($request->refurbisherType == 'LCD_Refurbished'){
                   $br = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                      $br =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }

                if($request->refurbisherType == 'LCD_Refurbished'){
                   $rep = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                       $rep =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();

                        $moved = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', 'Phone_Refurbished')
                        ->count();
                        //dd($data);
                    }else{
                         $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $bros = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }


                        //dd($data);
                    }else{
                         $bros = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();

                        foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }
                    }
                //  dd($stuckCount);
                 $bro = '';
                if($request->type == "Broken"){
                   if($request->refurbisherType == 'LCD_Refurbished'){
                    $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }

                // dd($bokn);
                }elseif($request->type == "Received"){
                   if($request->refurbisherType == 'LCD_Refurbished'){
                    $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }


                }elseif($request->type == "Released"){

                    if($request->refurbisherType == 'LCD_Refurbished'){
                        $marry = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.created_at', '<=', $to)
                        ->whereDate('lcd_issued_to.created_at', '>=', $from)
                        //->where('lcd_issued_to.status', '3')
                        // ->where('lcd_issued_to.receiver_name', 'Rainel')
                        // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.created_at', 'DESC')
                        ->orderBy('lcd_issued_to.created_at', 'DESC')->get();
                        //dd($data);
                    }else{
                         $marry = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        //->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }


                }elseif($request->type == "Stuck"){
                     if($request->refurbisherType == 'LCD_Refurbished'){
                        $broken =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                          $broken =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }
                    // dd($broken);

                }

           }else{
            //   dd($request);
                $from = date("Y-m-d",strtotime($request->to));
                // $f = time()-604800;
                $to = date("Y-m-d",strtotime($request->from));

                if($request->refurbisherType == 'LCD_Refurbished'){
                   $br = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                       $br =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
                if($request->refurbisherType == 'LCD_Refurbished'){
                   $rep = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                        //dd($data);
                    }else{
                       $rep =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }
                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();

                        $moved = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        // ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', 'Phone_Refurbished')
                        ->count();
                        //dd($data);
                    }else{
                         $mar = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->count();
                    }

                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $b = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }
                    }else{
                         $b = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($b as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(empty($dis)){
                                    array_push($ready,$x);
                                }
                            }
                        }
                    }


                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $d = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                         foreach($d as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }
                    }else{
                         $d = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                         foreach($d as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if($m){
                                $dis = Dispatch::where('inventory_id',$m->inventory_id)->first();
                                if(!empty($dis)){
                                    array_push($dispatch,$x);
                                }
                            }
                        }
                    }

                 if($request->refurbisherType == 'LCD_Refurbished'){
                        $bros = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();

                        foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }
                    }else{
                         $bros = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        foreach($bros as $x){
                            $m = AttachIMEIToLCD::where('lcd_inventory_id',$x->lcd_inventory_id)->first();
                            if(empty($m)){
                                    array_push($stuckCount,$x);
                            }
                        }
                    }


                if($request->type == "Broken"){
                   if($request->refurbisherType == 'LCD_Refurbished'){
                    $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $bokn = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '5')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }


                }elseif($request->type == "Received"){
                   if($request->refurbisherType == 'LCD_Refurbished'){
                    $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                        $data = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '4')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }


                }elseif($request->type == "Released"){

                    if($request->refurbisherType == 'LCD_Refurbished'){
                        $marry = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.created_at', '<=', $to)
                        ->whereDate('lcd_issued_to.created_at', '>=', $from)
                        //->where('lcd_issued_to.status', '3')
                        // ->where('lcd_issued_to.receiver_name', 'Rainel')
                        // ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                         $marry = DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        //->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }


                }elseif($request->type == "Stuck"){
                     if($request->refurbisherType == 'LCD_Refurbished'){
                        $broken =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                        ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        //->where('lcd_issued_to.receiver_name', 'Rainel')
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                        //dd($data);
                    }else{
                          $broken =  DB::table('lcd_issued_to')
                        ->join('lcd_inventories','lcd_issued_to.lcd_inventory_id','lcd_inventories.id')
                       ->whereDate('lcd_issued_to.updated_at', '<=', $to)
                        ->whereDate('lcd_issued_to.updated_at', '>=', $from)
                        ->where('lcd_issued_to.status', '3')
                        ->where('lcd_issued_to.assigned_to_account', $request->name)
                        ->where('lcd_issued_to.assigned_to', $request->refurbisherType)
                        ->orderBy('lcd_issued_to.updated_at', 'DESC')->get();
                    }
                    // dd($broken);

                }


           }
           return view('customer.reports.refurbisher_lcd_report', compact('data','marry','broken','user','rep','mar','bro','br','bokn','ready','udata','udata_to','udata_from','urefurbisherType','uname','utype','dispatch','stuckCount','moved'));

        }

    }



    //////////////summary reprot //////////////

    public function ModelSummary(Request $request)
    {
        $brands = Brand::all();
        $products = [];
        if ($request->isMethod('GET')) {
            return view('customer.reports.model_summary', compact('brands', 'products'));
        } elseif ($request->isMethod('POST')) {
            $where_array = array();
            $groupby = null;

            if ($request->has('brand')) {
                $brand = $request->get('brand');
                array_push($where_array, array('brand_id', '=', $brand));
            }
            if ($request->has('model')) {
                $model = $request->get('model');
                array_push($where_array, array('model', '=', $model));
                $groupby = 'model';
            }
            if ($request->has('network')) {
                $network = $request->get('network');
                array_push($where_array, array('network_id', '=', $network));
                $groupby = 'network_id';
            }
            if ($request->has('storage')) {
                $storage = $request->get('storage');
                array_push($where_array, array('storage_id', '=', $storage));
                $groupby = 'storage_id';
            }
            if ($request->has('color')) {
                $color = $request->get('color');
                array_push($where_array, array('color', '=', $color));
                $groupby = 'color';
            }
            if ($request->has('category')) {
                $category = $request->get('category');
                array_push($where_array, array('category_id', '=', $category));
            }


            $products = Lot::where($where_array)->orderBy('storage_id')->get();

            foreach ($products as $product){
                $dispatched = Inventory::where('lots_primary_key','=', $product->id)
                    ->where('status','=',0)->count();
                $product->dispatched = $dispatched;
            }

            // merge same variation
            $prev_obj = null;
            $run = true;
            $AP = array();

            foreach ($products as $prevKey=>$product){
                $prev_obj = $product;

                foreach ($AP as $afObj) {
                    if ($prev_obj->color == $afObj->color and $prev_obj->storage_id == $afObj->storage_id and $prev_obj->network_id == $afObj->network_id) {
                        $run = false;
                    }
                }
                if ($run)
                {
                    foreach ($products as $currentKey => $currentObj){
                        if  ($prevKey == $currentKey ){
                            continue;
                        }
                        elseif  ($prev_obj->color == $currentObj->color and $prev_obj->storage_id == $currentObj->storage_id and $prev_obj->network_id == $currentObj->network_id){
                            $prev_obj->asin_total_quantity = $prev_obj->asin_total_quantity + $currentObj->asin_total_quantity;
                            $prev_obj->inventory_quantity = $prev_obj->inventory_quantity + $currentObj->inventory_quantity;
                            $prev_obj->dispatched = $prev_obj->dispatched + $currentObj->dispatched;
                        }
                        else{
                            continue;
                        }
                    }
                    array_push($AP, $prev_obj);
                }
                $prev_obj = null;
                $run = true;
            }

            $products = $AP;

            return view('customer.reports.model_summary', compact('brands', 'products'));
        }
    }

    public function asin(Request $request){
        $brands = Brand::all();
        $products = [];
        if ($request->isMethod('GET')) {
            return view('customer.reports.asin', compact('brands', 'products'));
        }
        elseif ($request->isMethod('POST')) {
            $model = $request->get('model');
            $brand_id = $request->get('brand_id');

            $products = Lot::join('inventories', 'lots.id', '=', 'inventories.lots_primary_key')
                ->groupBy('asin')
                ->select(DB::Raw('sum(asin_total_quantity) as asin_total_quantity'),'lots.*')
                ->where('model', '=', $model)
                ->where('brand_id', '=', $brand_id)
                ->get();
            foreach ($products as $product){
                $dispatched = Inventory::where('lots_primary_key','=', $product->id)
                    ->where('status','=',0)
                    ->select(DB::Raw('count(lots_primary_key) as dispatched'))
                    ->value('dispatched');
                $product->dispatched = $dispatched;
                $available = Inventory::where('lots_primary_key','=', $product->id)
                    ->where('status','=',1)
                    ->select(DB::Raw('count(lots_primary_key) as available'))
                    ->value('available');
                $product->available = $available;
            }
            return view('customer.reports.asin', compact('brands', 'products'));
        }
    }

    public function modelSales(Request $request){
        $brands = Brand::all();
        $products = [];
        if ($request->isMethod('GET')) {
            return view('customer.reports.model_sales', compact('brands', 'products'));
        }
        elseif ($request->isMethod('POST')) {
            $model = $request->get('model');
            $brand_id = $request->get('brand_id');

            $from =  strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            $models = Lot::where('model', 'like', "{$model}%")
                ->where('brand_id', '=', $brand_id)
                ->select('model')
                ->groupBy('model')->get();

            foreach ($models as $model){
                $dispatched = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                    ->join('dispatches', 'inventories.id', '=', 'dispatches.inventory_id')
                    ->where('model','=', $model->model)
                    ->select(DB::Raw('count(inventory_id) as dispatched'))
                    ->whereBetween('dispatches.created_at', [$from, $to])
                    ->value('dispatched');
                $model->sold = $dispatched;
            }
            $products = [];
            foreach ($models as $item)
            {
                if ($item->sold > 0){
                    array_push($products, $item);
                }

            }
            return view('customer.reports.model_sales', compact('brands', 'products'));
        }
    }
    public function Tester(Request $request){
        $testing_performance = [];
        $testing_defeats = [];
        if ($request->isMethod('GET')) {
//            return view('customer.reports.testing', compact('testing_performance', 'testing_defeats'));
            /* }
             elseif ($request->isMethod('POST')) {*/

            $testings = null;

            if ($request->has('tester_id') && $request->get('tester_id') != '') {
                $tester_id = $request->get('tester_id');

                $from = strtotime($request->get('from'));
                $to = strtotime($request->get('to'));
                $date_inc = strtotime("+1 day", $to);
                $to = date("Y-m-d", $date_inc);
                $from = date("Y-m-d", $from);

                $testing_performance = Testing::select(DB::raw('DATE(created_at) as date'), DB::raw('count(inventory_id) as total_imei'))
                    ->groupBy('date')
                    ->where('created_by', '=', $tester_id)
                    ->whereBetween('created_at', [$from, $to])
                    ->get();

                $testing_defeats = Returns::join('testings', 'returns.inventory_id', '=', 'testings.inventory_id')
                    ->select(DB::raw('DATE(testings.created_at) as date'), DB::raw('count(returns.inventory_id) as total_imei'))
                    ->groupBy('date')
                    ->where('testings.created_by', '=', $tester_id)
                    ->whereBetween('testings.created_at', [$from, $to])
                    ->get();


                $testing_data = [];
                $testing_data_def = [];
                if ($request->has('date')){

                    $from = strtotime($request->get('date'));
                    $to = strtotime($request->get('date'));
                    $date_inc = strtotime("+1 day", $to);
                    $to = date("Y-m-d", $date_inc);
                    $from = date("Y-m-d", $from);

                    $testing_data = Testing::where('created_by', '=', $tester_id)
                        ->whereBetween('created_at', [$from, $to])
                        ->groupBy('inventory_id')
                        ->get();

                    $testing_data_def = Returns::join('testings', 'returns.inventory_id', '=', 'testings.inventory_id')
                        ->where('testings.created_by', '=', $tester_id)
                        ->whereBetween('testings.created_at', [$from, $to])
                        ->groupBy('testings.inventory_id')
                        ->get();

                }


                return view('customer.reports.testing', compact('testing_performance', 'testing_defeats', 'testing_data','testing_data_def','testings'));
            } elseif ($request->has('tester_id')) {
                $tester_id = $request->get('tester_id');

                $from = strtotime($request->get('from'));
                $to = strtotime($request->get('to'));
                $date_inc = strtotime("+1 day", $to);
                $to = date("Y-m-d", $date_inc);
                $from = date("Y-m-d", $from);

                $testing_performance = Testing::select(DB::raw('DATE(created_at) as date'), DB::raw('count(inventory_id) as total_imei'))
                    ->groupBy('date')
                    ->whereBetween('created_at', [$from, $to])
                    ->get();

                $testing_defeats = Returns::join('testings', 'returns.inventory_id', '=', 'testings.inventory_id')
                    ->select(DB::raw('DATE(testings.created_at) as date'), DB::raw('count(returns.inventory_id) as total_imei'))
                    ->groupBy('date')
                    ->whereBetween('testings.created_at', [$from, $to])
                    ->get();


                $testing_data = [];
                $testing_data_def = [];
                if ($request->has('date')){

                    $from = strtotime($request->get('date'));
                    $to = strtotime($request->get('date'));
                    $date_inc = strtotime("+1 day", $to);
                    $to = date("Y-m-d", $date_inc);
                    $from = date("Y-m-d", $from);

                    $testing_data = Testing::distinct('inventory_id')
                        ->whereBetween('created_at', [$from, $to])
                        ->groupBy('inventory_id')
                        ->get();

                    $testing_data_def = Returns::join('testings', 'returns.inventory_id', '=', 'testings.inventory_id')
                        ->whereBetween('testings.created_at', [$from, $to])
                        ->groupBy('testings.inventory_id')
                        ->get();

                }


                return view('customer.reports.testing', compact('testing_performance', 'testing_defeats', 'testing_data','testing_data_def','testings'));
            }
            elseif ($request->input('count_testing_by_imei')){
                $q = $request->input('imei');
                $testings = Testing::whereHas('inventory', function($query) use ($q){
                    $query->where('imei','=',$q);
                })->orderByDesc('created_at')->get();
                return view('customer.reports.testing', compact('testing_performance', 'testing_defeats','testings'));
            }
            else{
                return view('customer.reports.testing', compact('testing_performance', 'testing_defeats','testings'));
            }
        }
    }

    public function ModelSummary_2(Request $request)
    {
        $brands = Brand::all();
        $products = [];
        if ($request->isMethod('GET')) {
            return view('customer.reports.model_summary_2', compact('brands', 'products'));
        } elseif ($request->isMethod('POST')) {
            $where_array = array();

            if ($request->has('brand')) {
                $brand = $request->get('brand');
                array_push($where_array, array('brand_id', '=', $brand));
            }
            if ($request->has('model')) {
                $model = $request->get('model');
                array_push($where_array, array('model', '=', $model));
            }

//            $products = Lot::where($where_array)->orderBy('storage_id')->get();

            $colors = Lot::where($where_array)
                ->select('color')
                ->groupBy('color')->get();
//            $storages = Lot::where($where_array)->groupBy('storage_id')->get();

            foreach ($colors as $color){
                $storages = Lot::where('model', '=', $model)
                    ->where('brand_id', '=', $brand)
                    ->where('color', '=', $color->color)
                    ->select('storage_id')
                    ->groupBy('storage_id')->get();
                $color->storages = $storages;

                foreach ($color->storages as $storage){
                    $categories = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                        ->where('model', '=', $model)
                        ->where('brand_id', '=', $brand)
                        ->where('color', '=', $color->color)
                        ->where('storage_id', '=', $storage->storage_id)
                        ->select('category_id')
                        ->groupBy('category_id')->get();
                    $storage->categories = $categories;

                    foreach ($storage->categories as $category){
                        $category->available = 0;
                        $stock = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                            ->where('model', '=', $model)
                            ->where('brand_id', '=', $brand)
                            ->where('color', '=', $color->color)
                            ->where('storage_id', '=', $storage->storage_id)
                            ->where('category_id', '=', $category->category_id)
                            ->select(DB::Raw('count(imei) as stock'))
                            ->groupBy('category_id')->first()->stock;
                        $category->stock = $stock;

                        $available = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                            ->where('model', '=', $model)
                            ->where('brand_id', '=', $brand)
                            ->where('color', '=', $color->color)
                            ->where('storage_id', '=', $storage->storage_id)
                            ->where('category_id', '=', $category->category_id)
                            ->where('status', '=', 1)
                            ->select(DB::Raw('count(imei) as available'))
                            ->groupBy('category_id')->first();

                        if ($available){
                            $category->available = $available->available;
                        }
                        else{
                            $category->available = 0;
                        }
                    }
                }
            }
            $products = $colors;
            return view('customer.reports.model_summary_2', compact('brands', 'products'));
        }
    }

    public function reportDispatch(Request $request){
        $products = [];
        $vendors = Dispatch::groupBy('tracking')
            ->havingRaw('CHAR_LENGTH(tracking) < 10')
            ->get();
        /* if ($request->isMethod('GET')) {
             return view('customer.reports.dispatch', compact('vendors', 'products'));
         }
         elseif ($request->isMethod('POST')) {*/

        $from =  strtotime($request->get('from'));
        $to = strtotime($request->get('to'));
        $from = date("Y-m-d", $from);
        $date_inc = strtotime("+1 day", $to);
        $to = date("Y-m-d", $date_inc);
        $where_array = array();
        array_push($where_array, array('status', '=', 0));

        if ($request->has('tracking_id')) {
            $tracking_id = $request->get('tracking_id');
            array_push($where_array, array('tracking', '=', $tracking_id));
        }

        $products = Dispatch::select(DB::raw('DATE(created_at) as date'), DB::raw('count(inventory_id) as sold'))
            ->groupBy('date')
            ->where($where_array)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return view('customer.reports.dispatch', compact('vendors', 'products'));
//        }
    }

    public function reportDispatchExport(Request $request){


        $from =  strtotime($request->get('from'));
        $to = strtotime($request->get('to'));
        $from = date("Y-m-d", $from);
        $date_inc = strtotime("+1 day", $to);
        $to = date("Y-m-d", $date_inc);
        $where_array = array();
        array_push($where_array, array('status', '=', 0));

        if ($request->has('tracking_id')) {
            $tracking_id = $request->get('tracking_id');
            array_push($where_array, array('tracking', '=', $tracking_id));
        }

        $products = Dispatch::where($where_array)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        /*
                return  $products[0]->tracking;
                return  $products[0]->inventory->lot->storage->name;
                return  $products[0]->inventory->lot->network->name;
                return  $products[0]->inventory->lot->brand->name;
                return  $products[0]->inventory->category->name;*/

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('Brand','Model','Network','Storage','Color','Imei','Category','Tracking','Date'));

        foreach ($products as $product){

            if ($product->inventory) {
                $row = ['Brand' => $product->inventory->lot->brand->name, 'Model' => $product->inventory->lot->model, 'Network' => $product->inventory->lot->network->name, 'Storage' => $product->inventory->lot->storage->name, 'Color' => $product->inventory->lot->color, 'Imei' => $product->inventory->imei, 'Category' => $product->inventory->category->name, 'Tracking' => $product->tracking, 'Date' => $product->created_at];

                fputcsv($output, $row);
            }

        }
        fclose($output);

    }


    public function reportTesterExport(Request $request){


        $tester_id = $request->get('tester_id');

        $from =  strtotime($request->get('from'));
        $to = strtotime($request->get('to'));
        $from = date("Y-m-d", $from);
        $date_inc = strtotime("+1 day", $to);
        $to = date("Y-m-d", $date_inc);


        if ($request->has('tester_id')) {
            $products = Testing::where('created_by', '=', $tester_id)
                ->whereBetween('testings.created_at', [$from, $to])
                ->get();
        }
        else{
            $products = Testing::whereBetween('testings.created_at', [$from, $to])
                ->orderBy('created_by')
                ->get();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('Brand','Model','Network','Storage','Color','Imei','Category','Tester','Date','Status','Reason'));

        foreach ($products as $product){

            if ($product->inventory->lot) {
                $status = 'Fail';
                $fail_reasons = '' ;
                if(count(Problems::where('testing_id','=',$product->id)->where('status','=',0)->get()) < 1){
                    $status = 'Pass';
                }
                else{
                    $fail_reasons = Problems::where('testing_id','=',$product->id)->where('status','=',0)->pluck('problem_name')->toArray();
                    $fail_reasons = ''.implode(',',$fail_reasons).'';
                }


                $row = ['Brand' => $product->inventory->lot->brand->name, 'Model' => $product->inventory->lot->model, 'Network' => $product->inventory->lot->network->name, 'Storage' => $product->inventory->lot->storage->name, 'Color' => $product->inventory->lot->color, 'Imei' => $product->inventory->imei, 'Category' => $product->inventory->category->name, 'Tester' => $product->user->name, 'Date' => $product->created_at, 'Status' => $status, 'Reason' => $fail_reasons];
                fputcsv($output, $row);
            }
        }
        fclose($output);

    }

    public function ExportDispatchToDay(Request $request){
        $from =  strtotime(\Carbon\Carbon::now());
        $from = date("Y-m-d", $from);
        $dispatch = \App\Dispatch::where('created_at','>', $from)->get();


        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('Brand','Model','Network','Storage','Color','Imei','Category','Date','Tracking'));

        foreach ($dispatch as $product){

            if ($product->inventory->lot) {

                $row = ['Brand' => $product->inventory->lot->brand->name, 'Model' => $product->inventory->lot->model, 'Network' => $product->inventory->lot->network->name, 'Storage' => $product->inventory->lot->storage->name, 'Color' => $product->inventory->lot->color, 'Imei' => $product->inventory->imei, 'Category' => $product->inventory->category->name,'Date' => $product->created_at, 'Tracking' => $product->tracking];
                fputcsv($output, $row);
            }
        }
        fclose($output);

    }

    public function ExportRedFlag(Request $request)
    {
        if ($request->has('issued_to_for_report')){

            $expect_three_days =  strtotime(\Carbon\Carbon::now());
            $expect_three_days = strtotime("+1 day", $expect_three_days);
            $expect_three_days = date("Y-m-d", $expect_three_days);

            if ($request->has('from') and $request->has('to')) {
                $from = strtotime($request->get('from'));
                $to = strtotime($request->get('to'));
                $date_inc = strtotime("+1 day", $to);
                $to = date("Y-m-d", $date_inc);
                $from = date("Y-m-d", $from);

                $issued_to = $request->get('issued_to_for_report');

                if ($request->has('colors')){
                    $color = $request->get('colors');
                    $products = DB::select(DB::raw('SELECT l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.issued_to = :it AND w.created_at BETWEEN :from AND :to  AND l.color = :color order by w.id DESC '),
                        ['it' => $issued_to, 'from' => $from, 'to' => $to, 'color' => $color]);
                } else {
                    $products = DB::select(DB::raw('SELECT l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.issued_to = :it AND w.created_at BETWEEN :from AND :to order by w.id DESC '), ['it' => $issued_to, 'from' => $from, 'to' => $to]);
                }
            }
            else {
                $issued_to = $request->get('issued_to_for_report');
                $products = DB::select(DB::raw('SELECT l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.issued_to = :it  order by w.id DESC '), ['it' => $issued_to]);

            }
        }
        elseif ($request->has('from') and $request->has('to')) {
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            $expect_three_days =  strtotime(\Carbon\Carbon::now());
            $expect_three_days = strtotime("+1 day", $expect_three_days);
            $expect_three_days = date("Y-m-d", $expect_three_days);

            $products =  DB::select(DB::raw('SELECT l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.created_at BETWEEN :from AND :to order by w.id DESC '), ['from' => $from, 'to' => $to]);

        }
        elseif ($request->has('colors')){
            $color = $request->get('colors');
            $products = DB::select(DB::raw('SELECT l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND l.color = :color order by w.id DESC '), ['color' => $color]);
        }
        else{
            $products = [];
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('Model', 'Storage', 'Color', 'Imei', 'Category', 'Date', 'Tester'));

        foreach ($products as $product) {
            $row = ['Model' => $product->model, 'Storage' => $product->storage, 'Color' => $product->color, 'Imei' => $product->imei, 'Category' => $product->cat_name, 'Date' => $product->c_date, 'Tester' => $product->issued_to];
                fputcsv($output, $row);
        }
        fclose($output);

    }

    public function attachIMEIWithLCD(Request $request){
        $items = AttachIMEIToLCD::groupBy('created_by')->orderByDesc('id')
            ->simplePaginate(30);
        return view('customer.reports.imei_with_lcd_report', compact('items'));
    }
    public function brokenListReport(Request $request){
        $items = LcdInventory::where('status','=',4)
            ->orderByDesc('id')
            ->simplePaginate(30);
        return view('customer.reports.broken_list_report', compact('items'));
    }

    public function lcdInventoryReport(Request $request){
        $items = [];
        if ($request->get('status') and $request->get('refurbisher') and $request->get('account')){
            $status = $request->get('status');
            $refurbisher = $request->get('refurbisher');
            $account = $request->get('account');

            if ($request->input('refurbisher') == 'LCD_Refurbished') {
                $items = LcdInventory::with('issued')->whereHas('issued', function ($query) use ($refurbisher,$account) {
                    $query->where('assigned_to', '=', $refurbisher);
                    $query->where('receiver_name', '=', $account);
                })->where('status', '=', $status+2)
                    ->orderByDesc('id')
                    ->simplePaginate(50);
            }else{
                $items = LcdInventory::with('issued')->whereHas('issued', function ($query) use ($refurbisher,$account) {
                    $query->where('assigned_to', '=', $refurbisher);
                    $query->where('assigned_to_account', '=', $account);
                })->where('status', '=', $status+2)
                    ->orderByDesc('id')
                    ->simplePaginate(50);
            }
        }
        elseif ($request->get('from') and $request->input('status')){
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);
            $status = $request->input('status');
            $items = LcdInventory::where('status', '=', $status+2)
                ->whereBetween('created_at', [$from, $to])
                ->orderByDesc('id')
                ->simplePaginate(50);
        }
        elseif ($request->input('status')) {
            $status = $request->input('status');
            $items = LcdInventory::where('status', '=', $status+2)
                ->orderByDesc('id')
                ->simplePaginate(50);
        }elseif ($request->get('from')){
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            $items = LcdInventory::whereBetween('created_at', [$from, $to])
                ->orderByDesc('id')
                ->simplePaginate(50);
        }
        elseif ($request->input('ajax')){
            $referbisher = $request->input('refurbisher');
            if ($request->input('refurbisher') == 'LCD_Refurbished'){
                return $items = LcdIssuedTo::where('assigned_to','=', $referbisher)
                    ->groupBy('receiver_name')->get();
            }else{
                return $items = LcdIssuedTo::with('user')->where('assigned_to','=', $referbisher)
                    ->groupBy('assigned_to_account')->get();
            }
        }
        return view('customer.reports.lcd_inventory_report', compact('items'));
    }


}

