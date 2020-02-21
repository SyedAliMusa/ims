<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Inventory;
use App\Lot;
use App\Network;
use App\Storages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LotController extends Controller
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

        if ($request->has('filter')){
            $products = Lot::groupBy('lot_id')
                ->where('brand_id','=', $request->get('brand_id'))
                ->where('model','=', $request->get('model'))
                ->orderByDesc('created_at')
                ->simplePaginate(100);
            $products->total = Lot::groupBy('lot_id')
                ->where('brand_id','=', $request->get('brand_id'))
                ->where('model','=', $request->get('model'))->get()->count();

        }
        elseif ($request->has('query')){
            $products = Lot::groupBy('lot_id')
                ->where('lot_id','like', "%{$request->get('query')}%")
                ->orWhere('model','like', "%{$request->get('query')}%")
                ->orWhere('asin','like', "%{$request->get('query')}%")
                ->orderByDesc('created_at')
                ->simplePaginate(100);

            $products->total = Lot::groupBy('lot_id')
                ->where('lot_id','like', "%{$request->get('query')}%")
                ->orWhere('model','like', "%{$request->get('query')}%")
                ->orWhere('asin','like', "%{$request->get('query')}%")
                ->get()->count();
        }
        else{
            $products = Lot::groupBy('lot_id')
                ->orderByDesc('created_at')
                ->simplePaginate(30);

            $products->total = Lot::groupBy('lot_id')->get()->count();
        }


        return view('customer.lots.index', compact('products','brands'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $networks = Network::all();
        $storages = Storages::all();
        return view('customer.lots.create', compact('brands','networks','storages'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Lot::insertGetId([
            'lot_id'=> $request->get('lot_id'),
            'brand_id'=> $request->get('brand'),
            'model'=> $request->get('model'),
            'network_id'=> $request->get('network'),
            'color'=> $request->get('color'),
            'storage_id'=> $request->get('storage'),
            'asin'=> $request->get('asin'),
            'asin_total_quantity'=> $request->get('quantity'),
            'received_quantity'=> $request->get('received_qty'),
            'bought_quantity'=> $request->get('bought_qty'),
            'created_by'=> Auth::id(),
        ]);
        if (isset($id)){
            $lot = Lot::find($id);
            $asin_total_quantity = Lot::where('lot_id','=',$lot->lot_id)
                ->select(DB::raw('SUM(asin_total_quantity) as asin_total_quantity'))
                ->groupBy('lot_id')
                ->value('asin_total_quantity');
            $lot->asin_total_quantity = $asin_total_quantity;
            return $lot;
        }
        else{
            return 0;
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
        $products = Lot::where('lot_id','=',$lot->lot_id)->get();

        return view('customer.lots.show',compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $storages = Storages::all();
        $product = Lot::find($id);
        return view('customer.lots.update_asin', compact('product','storages'));
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
        $color = $request->get('color');
        $storage_id = $request->get('storage_id');
        $new_quantity = $request->get('quantity');
        $lot = Lot::find($id);
        $total_quantity = Lot::where('lot_id','=',$lot->lot_id)
            ->select(DB::raw('SUM(asin_total_quantity) as total_quantity'))
            ->groupBy('lot_id')
            ->value('total_quantity');

        $new_asin_quantity = ($total_quantity - $lot->asin_total_quantity) + $new_quantity;
        if ($lot->received_quantity < $new_asin_quantity){
            return redirect()->back()->with(['quantity_exceed' => 'Asin quantity exceed from LOT quantity']);
        }
        else{
            Lot::where('id','=', $id)
                ->update([
                   'color'=> $color,
                   'storage_id'=> $storage_id,
                   'asin_total_quantity'=> $new_quantity,
                ]);
            return redirect()->back()->with(['success' => 'Asin record has been updated']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lot = Lot::find($id);
        if ($lot->inventory_quantity > 0 ){
            Lot::where('id','=',$id)->delete();
            Inventory::where('lots_primary_key','=',$id)->delete();
//            return redirect()->route('lots.index');
            return redirect()->back();
        }
        else{
            Lot::where('id','=',$id)->delete();
            return redirect()->route('lots.index');
        }
    }

    public function addMoreLot( Request $request, $id)
    {
        $storages = Storages::all();
        $product = Lot::find($id);
//        return $product->brand->name;
        if ($request->isMethod('GET')) {
            return view('customer.lots.add_more', compact('product','storages'));
        }
        elseif ($request->isMethod('POST')) {
            $lot = Lot::find($id);
            $id = Lot::insertGetId([
                'lot_id'=> $lot->lot_id,
                'brand_id'=> $lot->brand_id,
                'model'=> $lot->model,
                'network_id'=> $lot->network_id,
                'color'=> $request->get('color'),
                'storage_id'=> $request->get('storage_id'),
                'asin'=> $request->get('asin'),
                'asin_total_quantity'=> $request->get('quantity'),
                'received_quantity'=> $lot->received_quantity,
                'bought_quantity'=> $lot->bought_quantity,
                'created_by'=> Auth::id(),
            ]);
            if (isset($id)){
                $lot = Lot::find($id);
                $asin_total_quantity = Lot::where('lot_id','=',$lot->lot_id)
                    ->select(DB::raw('SUM(asin_total_quantity) as asin_total_quantity'))
                    ->groupBy('lot_id')
                    ->value('asin_total_quantity');
                $lot->asin_total_quantity = $asin_total_quantity;
                return $lot;
            }
            else{
                return 0;
            }

        }
    }
}
