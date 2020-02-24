<?php

namespace App\Http\Controllers;

use App\Category;
use App\Inventory;
use App\Problems;
use App\Returns;
use App\Testing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestingController extends Controller
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
        $products = Testing::groupby('created_by')->orderByDesc('id')->paginate(50);
        return view('customer.testing.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $product = null;
        $performance = [];
        $defeats = [];

        if ($request->has('from')) {
            $tester_id = Auth::id();
            $from =  strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            $performance = Inventory::join('testings', 'inventories.id', '=', 'testings.inventory_id')
                ->select(DB::raw('DATE(testings.created_at) as date'), DB::raw('count(testings.inventory_id) as total_imei'))
                ->groupBy('date')
                ->where('testings.created_by', '=', $tester_id)
                ->whereBetween('testings.created_at', [$from, $to])
                ->get();

            $defeats = Inventory::join('testings', 'inventories.id', '=', 'testings.inventory_id')
                ->join('returns', 'inventories.id', '=', 'returns.inventory_id')
                ->select(DB::raw('DATE(returns.created_at) as date'), DB::raw('count(returns.inventory_id) as total_imei'))
                ->groupBy('date')
                ->where('testings.created_by', '=', $tester_id)
                ->havingRaw('total_imei > 1')
                ->whereBetween('testings.created_at', [$from, $to])
                ->get();

        }
        return view('customer.testing.create',compact('categories','product','performance','defeats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
     public function release_phone_for_refurbisher(){
          $user = DB::table("users")->where('account_type','refurbishing')->get();
         return view('customer.testing.release_phone_for_refurbisher', compact('user'));
     }
     
     
    public function store(Request $request)
    {
        // dd($request);
//        return $request->all();
//        print_r($problems = $request->except(['_token', 'imei', 'category']));die;
        $imei = $request->get('imei');
        $category_id_new = $request->get('category');
        $inventory = Inventory::where('imei', '=', $imei)->first();
        if ($inventory) {
            $category_id_old = $inventory->category_id;


            $res = Inventory::where('id','=',$inventory->id)
                ->update([
                    'category_id' => $category_id_new,
                ]);
            //insert record in testing
            $testing_id = Testing::insertGetId([
                'inventory_id' => $inventory->id,
                'category_id_old' => $category_id_old,
                'created_by' => Auth::id(),
            ]);
            $problems = $request->except(['_token', 'imei', 'category']);

            foreach ($problems as $key => $value) {
                $result = Problems::insert([
                    'testing_id' => $testing_id,
                    'problem' => $key,
                    'problem_name' => $value,
                    'created_by' => Auth::id(),
                ]);
            }
            return redirect()->back()->with(['message' => 'Record has been added for testing.']);
        }
        return redirect()->back()->with(['message' => 'IMEI not found']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $created_by)
    {
        if ($request->has('query')){
            $products = Testing::join('inventories','testings.inventory_id','=','inventories.id')
                ->where('testings.created_by','=',$created_by)
                ->Where('imei','=', $request->get('query'))
                ->orderByDesc('id')
                ->simplePaginate(1000);
        }
        else{
            $products = Testing::where('created_by','=',$created_by)
                ->select('testings.*', DB::Raw('count(inventory_id) as count'))
                ->groupBy('inventory_id')
                ->orderByDesc('id')
                ->simplePaginate(50);
        }
        return view('customer.testing.show',compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($testing_inv_id)
    {

        $testing_id = array();
//        $testing_ids = DB::select(DB::raw('SELECT * from testings WHERE inventory_id = :inv'), ['inv' => $testing_inv_id]);
        $testing_ids = DB::select(DB::raw('SELECT t.id,t.category_id_old,t.created_at, t.returned, i.category_id, i.imei, u.name, l.model,ct.name as new_cat, ctt.name as old_cat from testings t 
                                INNER JOIN inventories i on i.id = t.inventory_id INNER JOIN users u on u.id = t.created_by INNER JOIN lots l on l.id = i.lots_primary_key 
                                INNER JOIN categories ct on ct.id = i.category_id INNER JOIN categories ctt ON ctt.id = t.category_id_old 
                                WHERE t.inventory_id = :inv order by t.id DESC '), ['inv' => $testing_inv_id]);

        $return = DB::select(DB::raw('select r.*,u.name from returns r inner join 
                                      users u on u.id = r.created_by where inventory_id = :inv'), ['inv' => $testing_inv_id]);
//        $return = Returns::where('inventory_id','=',$testing_inv_id)->select('id')->orderByDesc('id')->first();
//print_r($return);die;
        if ($return){
            foreach ($return as $k => $v) {
              /*  $data['return_message'][] = $v->message;
                $data['return_date'][] = $v->created_at;*/
                $data = $return;/*[
                    'return_message' => $v->message,
                    'return_date' => $v->created_at,
                    'testing_id' => $v->testing_id,
                    'return_data' => $return,
                ];*/
            }
            /*$data =[
                'return_message' => $return->message,
                'return_date' => $return->created_at,
            ];*/
        }
        else{
            $data = 0;
        }
//print_r($data[0]->name);die;
        return view('customer.testing.problem_updated',compact('testing_ids','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $testing_id)
    {
        $problems = $request->except(['_token', '_method', 'category']);
        foreach ($problems as $key => $value) {
            $result = Problems::where('testing_id','=',$testing_id)
                ->where('problem','=',$key)
                ->update([
                    'status' => 1,
                ]);
        }
        $testing = Testing::find($testing_id);
        $res = DB::select(DB::raw('SELECT * FROM inventories WHERE id = :id'), ['id' => $testing->inventory_id]);
        $category_id_old = $res[0]->category_id;
        $inv = Inventory::where('id','=',$testing->inventory_id)
            ->update([
                'category_id' => $request->get('category'),
            ]);
        $tes = Testing::where('id','=',$testing_id)
            ->update([
                'category_id_old' => $category_id_old,
            ]);
        return redirect()->back()->with(['message' => 'Record has been updated.']);
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


    public function testAgain(Request $request, $id){
        $categories = Category::all();
        $product = Testing::find($id);
        $performance = [];
        $defeats = [];

        return view('customer.testing.create',compact('categories','product','performance','defeats'));
    }

    public function testRecords(Request $request){

        if ($request->has('imei')){
            $products = DB::select( DB::raw("SELECT u.name as user_name,t.created_at,c.name as cat_old_name, cc.name as cat_new_name FROM inventories i 
                                   Inner join testings t ON i.id = t.inventory_id INNER JOIN categories c 
                                   ON t.category_id_old = c.id INNER JOIN users u ON t.created_by = u.id 
                                  inner join categories cc on cc.id = i.category_id
                                   WHERE i.imei = :imei order by created_at DESC "), array(
                'imei' => $request->get('imei'),
            ));
        } else {
            $products = [];
        }

        return view('customer.testing.test_record',compact('products'));
    }
}
