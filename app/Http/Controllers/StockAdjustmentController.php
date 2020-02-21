<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Inventory;
use App\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    
    public function __construct()
    {
       
        date_default_timezone_set("America/New_York");
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::all();
        $products = [];
        $verified = [];

        if ($request->has('brand_id')){
            $brand_id = $request->get('brand_id');
            $model = $request->get('model');

            $verified =Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                ->where('verified','=',1) // means verified
                ->where('status','=',1) // available
                ->where('brand_id','=',$brand_id)
                ->where('model','=',$model)
                ->select('inventories.*')
                ->orderByDesc('updated_at')
                ->paginate(30);

            $products = Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                ->where('brand_id','=',$brand_id)
                ->where('model','=',$model)
                ->where('verified','=',0) // means not verified / extra
                ->where('status','=',1) // available
                ->select('inventories.*')
                ->paginate(30);
        }
        return view('customer.stock_adjustment.index',compact('brands','products','verified'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getVerify(Request $request)
    {
        $brand_id = $request->get('brand_id');
        $model = $request->get('model');
        $imei = $request->get('imei');

        $verified =Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
//        ->where('verified','=',0) // means not verified
            ->where('status','=',1) // available
            ->where('brand_id','=',$brand_id)
            ->where('model','=',$model)
            ->where('imei','=',$imei)
            ->select('inventories.id','inventories.verified')
            ->first();
        if ($verified){
            if ($verified->verified == 1){
                return redirect()->back()->with(['already_verified' => 'This '.$imei.' has already been verified']);
            }
            else {
                $res = Inventory::where('id', '=', $verified->id)
                    ->update([
                        'verified' => 1
                    ]);
                return redirect()->back()->with(['success' => 'Verified']);
            }
        }
        else{
            $is_exist_in_inventory =Inventory::join('lots', 'inventories.lots_primary_key', '=', 'lots.id')
                ->where('imei','=',$imei)->first();
            if ($is_exist_in_inventory){
                return redirect()->back()->with(['fail' => 'Not available in this model but have in model ('.$is_exist_in_inventory->model.')']);
            }
            else{
                return redirect()->back()->with(['fail' => 'Not available in "Inventory"']);
            }
        }
    }
}
