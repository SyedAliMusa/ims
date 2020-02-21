<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Dispatch;
use App\Inventory;
use App\Lot;
use App\Returns;
use App\Testing;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        if ($request->has('filter')){
            $products = Lot::groupBy('lot_id')
                ->where('brand_id','=', $request->get('brand_id'))
                ->where('model','=', $request->get('model'))
                ->orderByDesc('id')
                ->simplePaginate(500);
        }
        elseif ($request->has('query')){
            $products = Lot::groupBy('lot_id')
                ->where('lot_id','like', "%{$request->get('query')}%")
                ->orWhere('model','like', "%{$request->get('query')}%")
                ->orWhere('asin','like', "%{$request->get('query')}%")
                ->orderByDesc('id')
                ->simplePaginate(500);
        }
        else{
            $products = Lot::groupBy('lot_id')
                ->orderByDesc('id')
                ->simplePaginate(30);
        }
        return view('customer.inventory.index',compact('products','brands'));

    }

    /**
     * Show the form for creating a new resource.$brand_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lots = Lot::select('lot_id')
            ->groupBy('lot_id')
            ->orderByDesc('id')
            ->get();
        $categories = Category::all();
        return view('customer.inventory.create',compact('lots','categories'));
    }
    public function Quick_create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('customer.inventory.quck_create',compact('categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imei_is_exist = Inventory::where('imei' ,'=', $request->get('imei'))->value('imei');

        if ($imei_is_exist)
        {
            return 123456789;
        }
        else
        {
             $has_lot = Lot::where('lot_id','=', $request->get('lot_id'))
                ->where('color','=', $request->get('color'))
                ->where('storage_id','=', $request->get('storage_id'))
                ->where('asin','=', $request->get('asin'))->first();

             if ($has_lot){
                 $inventory_quantity = $has_lot->inventory_quantity + 1;
                 $res = Lot::where('id','=', $has_lot->id)
                     ->update(['inventory_quantity' => $inventory_quantity]);

                 $result = Inventory::insert([
                     'lots_primary_key' => $has_lot->id,
                     'category_id' => $request->get('category_id'),
                     'imei' => $request->get('imei'),
                     'verified' => 1,
                     'created_by' => Auth::id(),
                 ]);
             }
            if ($result)
            {
                return $has_lot->asin_total_quantity - $inventory_quantity ;
            } else
            {
                return 0;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lot = Lot::find($id);
        $lot_entry_id_arr = Lot::where('lot_id','=',$lot->lot_id)->pluck('id')->toArray();
        $products = Inventory::whereIn('lots_primary_key',$lot_entry_id_arr)->orderByDesc('id')->get();
        $products->total = Inventory::whereIn('lots_primary_key',$lot_entry_id_arr)->get()->count();


        return view('customer.inventory.show',compact('products'));
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
        $inventory = Inventory::find($id);
       $lot = Lot::where('id', '=', $inventory->lots_primary_key)->first();

        $inventory_quantity = $lot->inventory_quantity - 1;

        Lot::where('id', '=', $inventory->lots_primary_key)
        ->update([
            'inventory_quantity' => $inventory_quantity
        ]);

        Testing::where('inventory_id','=',$inventory->id)->delete();
        Dispatch::where('inventory_id','=',$inventory->id)->delete();
        Returns::where('inventory_id','=',$inventory->id)->delete();
        Inventory::find($id)->delete();
        return redirect()->back();
    }
    public function changeCategory(Request $request, $id)
    {
        Inventory::where('id', '=', $id)
        ->update([
            'category_id' => $request->get('category_id')
        ]);

        return redirect()->back();
    }
}
