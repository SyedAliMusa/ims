<?php

namespace App\Http\Controllers;

use App\Dispatch;
use App\Inventory;
use App\Problems;
use App\Returns;
use App\Testing;
use App\WarehouseInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
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
        if ($request->has('query')){
            $products = Returns::join('inventories','returns.inventory_id','=','inventories.id')
                ->orWhere('imei','like', "%{$request->get('query')}%")
                ->simplePaginate(100);
        }
        else {
            $products = Returns::orderByDesc('id')->simplePaginate(30);
        }
        return view('customer.returns.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.returns.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imei = $request->get('imei');
        $comment = $request->get('comment');
        $has_inventory = Inventory::where('imei','=',$imei)->first();
        if ($has_inventory)
        {
            // status 0 means dipatched not returned
            $has_dispatched = Dispatch::where('inventory_id','=',$has_inventory->id)
                ->where('status','=', 0)->first();
            if ($has_dispatched)
            {
                $res = WarehouseInOut::where('inventory_id',$has_inventory->id)->delete();

                if ($request->has('category_id')){
                    $category_id_new = $request->get('category_id');
                    $category_id_old = $has_inventory->category_id;

                    $res = Inventory::where('id','=',$has_inventory->id)
                        ->update([
                            'category_id' => $category_id_new,
                        ]);
                    //insert record in testing
                    $testing_id = Testing::insertGetId([
                        'inventory_id' => $has_inventory->id,
                        'category_id_old' => $category_id_old,
                        'returned' => true,
                        'created_by' => Auth::id(),
                    ]);
                    $problems = $request->except(['_token', 'imei','comment', 'category_id']);

                    foreach ($problems as $key => $value) {
                        $result = Problems::insert([
                            'testing_id' => $testing_id,
                            'problem' => $key,
                            'problem_name' => $value,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }

                $result = Returns::insert([
                    'testing_id' => $testing_id,
                    'inventory_id' => $has_inventory->id,
                    'message' => $comment,
                    'created_by' => Auth::id(),
                ]);

                $result = Dispatch::where('inventory_id','=',$has_inventory->id)
                    ->where('status','=', 0)->update([
                        'status' => 1
                    ]);
                $result = Inventory::where('id', '=', $has_inventory->id)
                    ->update([
                        'status' => 1,
                    ]);

                return redirect()->back()->with(['message' => ' Record Insert Successfully']);
            }
            else
            {
                return redirect()->back()->with(['error' => ' imei not found! means imei is not exist in dispatch table ']);
            }
        }

        else{
            return redirect()->back()->with(['error' => 'imei not found']);

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
}
