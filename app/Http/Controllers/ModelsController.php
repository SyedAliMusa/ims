<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModelsController extends Controller
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
        $products = DB::select(DB::raw('select m.*,b.name as bname from models m inner join brands b on b.id = m.brand_id order  by id DESC '));
        $brands = Brand::all();
        return view('customer.models.index',compact('products','brands'));
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

        $network  = $request->get('brand');
        $model  = $request->get('model');
        $has_model = DB::select(DB::raw('select * from models where name = :model AND brand_id = :bid order  by id DESC '),['model' => $model, 'bid' => $network]);

        if ($has_model){
            return redirect()->back()->with(['error' => 'Model already exist']);
        }
        else {
            $data = array('brand_id'=>$network,'name'=>$model,'created_at'=>date("Y-m-d h:i:s",time()),'updated_at'=>date("Y-m-d h:i:s",time()));

            $result = DB::table('models')->insert($data);
            return redirect()->back()->with(['error' => 'Model Added']);
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
