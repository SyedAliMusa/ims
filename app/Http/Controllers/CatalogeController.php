<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Inventory;
use App\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogeController extends Controller
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
    public function index()
    {
        $brands = Brand::all();
        $products = [];
        return view('customer.cataloge.index',compact('brands','products'));
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
    public function show(Request $request, $id)
    {
        $brands = Brand::all();

        $brand_id = $request->get('brand_id');
        if ($request->input('model')) {
            $model = $request->get('model');

            $products = Lot::where('brand_id', '=', $brand_id)
                ->where('model', '=', $model)->orderBy('storage_id')->get();

            // merge same variation
            $prev_obj = null;
            $run = true;
            $AP = array();

            foreach ($products as $prevKey => $product) {
                $prev_obj = $product;

                foreach ($AP as $afObj) {
                    if ($prev_obj->color == $afObj->color and $prev_obj->storage_id == $afObj->storage_id and $prev_obj->network_id == $afObj->network_id) {
                        $run = false;
                    }
                }
                if ($run) {
                    foreach ($products as $currentKey => $currentObj) {
                        if ($prevKey == $currentKey) {
                            continue;
                        } elseif ($prev_obj->color == $currentObj->color and $prev_obj->storage_id == $currentObj->storage_id and $prev_obj->network_id == $currentObj->network_id) {
                            $prev_obj->asin_total_quantity = $prev_obj->asin_total_quantity + $currentObj->asin_total_quantity;
                            $prev_obj->inventory_quantity = $prev_obj->inventory_quantity + $currentObj->inventory_quantity;
//                        $prev_obj->dispatched = $prev_obj->dispatched + $currentObj->dispatched;
                        } else {
                            continue;
                        }
                    }
                    array_push($AP, $prev_obj);
                }
                $prev_obj = null;
                $run = true;
            }
            $products = $AP;
            return view('customer.cataloge.index', compact('brands', 'products'));
        }
        else{
            $lots =  $this->brandVariationExport($brand_id);
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $output = fopen("php://output", "w");
            fputcsv($output, array('Brand', 'Model', 'Network', 'Storage', 'Color', 'Category'));

            foreach ($lots as $lot) {
               $inventories = $lot->inventory()->where('status','=', '1')->groupBy('category_id')->orderBy('category_id')->get();
                if (count($inventories) > 0) {
                    foreach ($inventories as $inventory) {
                        $row = ['Brand' => $lot->brand->name, 'Model' => $lot->model, 'Network' => $lot->network->name, 'Storage' => $lot->storage->name, 'Color' => $lot->color, 'Category' => $inventory->category->name];
                        fputcsv($output, $row);
                    }
                }
            }
            fclose($output);
//            return $lots[0]->inventory()->where('status','=', '1')->groupBy('category_id')->orderBy('category_id')->get();
        }
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

    public function brandVariationExport($brand_id)
    {
        return  $models =  Lot::where('brand_id','=', $brand_id)
            ->get();
    }
}
