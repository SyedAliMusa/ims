<?php

namespace App\Http\Controllers;

use App\AmazonExcelFile;
use App\AttachIMEIToLCD;
use App\Inventory;
use App\Lot;
use App\SimpleXLSX;
use App\WarehouseInOut;
use App\IMEI;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use Illuminate\Support\Facades;


class WareHouseController extends Controller
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
        $products = Inventory::leftJoin('warehouse_in_out','inventories.id','=','warehouse_in_out.inventory_id')
            ->where('status','=',1)
            ->where('inventories.id','<>','warehouse_in_out.inventory_id')
            ->orderByDesc('inventories.id')
            ->simplePaginate(20);

        $products->total = Inventory::leftJoin('warehouse_in_out','inventories.id','=','warehouse_in_out.inventory_id')
            ->where('status','=',1)
            ->where('inventories.id','<>','warehouse_in_out.inventory_id')
            ->get()->count();

        return view('customer.warehouse.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->has('issued_to_for_report')) {
            $issued_to = $request->get('issued_to_for_report');
            if ($request->has('from') and $request->has('to')) {
                $from = strtotime($request->get('from'));
                $to = strtotime($request->get('to'));
                $date_inc = strtotime("+1 day", $to);
                $to = date("Y-m-d", $date_inc);
                $from = date("Y-m-d", $from);

                if ($request->has('colors')) {
                    $color = $request->get('colors');
                    $products = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to, $color) {
                        $query->where('status', '=', 1);
                        $query->where('issued_to', '=', $issued_to);
                        $query->where('color_folder','=', $color);
                    })->orderByDesc('id')
                        ->whereBetween('warehouse_in_out.created_at', [$from, $to])
                        ->simplePaginate(1000);

                    $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to, $color) {
                        $query->where('status', '=', 1);
                        $query->where('issued_to', '=', $issued_to);
                        $query->where('color_folder','=', $color);
                    })->whereBetween('warehouse_in_out.created_at', [$from, $to])
                        ->get()->count();
                } else {
                    $products = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to) {
                        $query->where('status', '=', 1);
                        $query->where('issued_to', '=', $issued_to);
                    })->orderByDesc('id')
                        ->whereBetween('warehouse_in_out.created_at', [$from, $to])
                        ->simplePaginate(1000);

                    $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to) {
                        $query->where('status', '=', 1);
                        $query->where('issued_to', '=', $issued_to);
                    })->whereBetween('warehouse_in_out.created_at', [$from, $to])
                        ->get()->count();
                }
            } elseif ($request->has('colors')) {
                $color = $request->get('colors');
                $products = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to, $color) {
                    $query->where('status', '=', 1);
                    $query->where('issued_to', '=', $issued_to);
                    $query->where('color_folder','=', $color);
                })->orderByDesc('id')
                    ->simplePaginate(1000);

                $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to, $color) {
                    $query->where('status', '=', 1);
                    $query->where('issued_to', '=', $issued_to);
                    $query->where('color_folder','=', $color);
                })->get()->count();
            } else {
                $issued_to = $request->get('issued_to_for_report');
                $products = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to) {
                    $query->where('status', '=', 1);
                    $query->where('issued_to', '=', $issued_to);
                })->orderByDesc('id')
                    ->simplePaginate(1000);

                $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($issued_to) {
                    $query->where('status', '=', 1);
                    $query->where('issued_to', '=', $issued_to);
                })->get()->count();
            }
        }
        elseif ($request->has('from') and $request->has('to')) {
            $from = strtotime($request->get('from'));
            $to = strtotime($request->get('to'));
            $date_inc = strtotime("+1 day", $to);
            $to = date("Y-m-d", $date_inc);
            $from = date("Y-m-d", $from);

            if ($request->has('colors')) {
                $color = $request->get('colors');
                $products = WarehouseInOut::whereHas('inventory', function ($query) use ($color) {
                    $query->where('status', '=', 1);
                    $query->where('color_folder','=', $color);
                })->orderByDesc('id')
                    ->whereBetween('warehouse_in_out.created_at', [$from, $to])
                    ->simplePaginate(1000);

                $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($color) {
                    $query->where('status', '=', 1);
                    $query->where('color_folder','=', $color);
                })->whereBetween('warehouse_in_out.created_at', [$from, $to])
                    ->get()->count();
            } else {
                $products = WarehouseInOut::whereHas('inventory', function ($query) {
                    $query->where('status', '=', 1);
                })->orderByDesc('id')
                    ->whereBetween('warehouse_in_out.created_at', [$from, $to])
                    ->simplePaginate(1000);

                $products->total = WarehouseInOut::whereHas('inventory', function ($query) {
                    $query->where('status', '=', 1);
                })->whereBetween('warehouse_in_out.created_at', [$from, $to])
                    ->get()->count();
            }

        } elseif ($request->has('colors')) {
            $color = $request->get('colors');
            $products = WarehouseInOut::whereHas('inventory', function ($query) use ($color) {
                $query->where('status', '=', 1);
                $query->where('color_folder','=', $color);
            })->orderByDesc('id')
                ->simplePaginate(1000);

            $products->total = WarehouseInOut::whereHas('inventory', function ($query) use ($color) {
                $query->where('status', '=', 1);
                $query->where('color_folder','=', $color);
            })->get()->count();
        }
        else{
            $products = [];
        }

        return view('customer.warehouse.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function release_by_tester(Request $request){
        $imei = $request->get('imei');
        $issued_to = $request->get('issued_to');
         $is_exist =Inventory::where('status','=',1) // available
        ->where('imei','=',$imei)->first();
        if ($is_exist){
            $already_exist_in =WarehouseInOut::where('inventory_id','=',$is_exist->id)->where('issued_to','=','Tester')->first();

            if ($already_exist_in){
                $result = WarehouseInOut::where('id','=',$already_exist_in->id)
                        ->update([
                            'issued_to' => $issued_to,
                            'created_by' => Auth::id(),
                            'Account' => $request->Account,
                            'acc_status' => '2',
                        ]);

                return redirect()->back()
                    ->with(['success_release' => 'Success: stocked out', 'issued_to' => $issued_to,'Account'=>$request->Account]);
            }else{
                 return redirect()->back()
                    ->with(['already_verified' => 'Repeat: This '.$imei.' has Not Issued to Tester', 'issued_to' => $issued_to,'Account'=>$request->Account]);
            }
        }

     }

    public function store(Request $request)
    {
        $imei = $request->get('imei');
        $issued_to = $request->get('issued_to');

        $is_exist =Inventory::where('status','=',1) // available
        ->where('imei','=',$imei)->first();

        if ($is_exist){
            $already_exist_in =WarehouseInOut::where('inventory_id','=',$is_exist->id)->first();

            if ($already_exist_in){
                return redirect()->back()
                    ->with(['already_verified' => 'Repeat: This '.$imei.' has already been checked Out', 'issued_to' => $issued_to,'color_folder' => $request->get("color_folder"),'Account'=>$request->Account]);
                /*return redirect()->route('warehouse.create',['issued_to'=>$issued_to])
                    ->with(['already_verified' => 'Repeat: This '.$imei.' has already been checked Out']);*/
            }
            else {
                $data = DB::select(DB::raw('SELECT n.name as network_name, s.name as storage_name ,c.name, l.id, l.model, l.color, l.network_id, l.storage_id, i.category_id from lots l
	                    JOIN inventories i on l.id = i.lots_primary_key JOIN categories c on i.category_id = c.id join storages s on l.storage_id = s.id
                        JOIN networks n on l.network_id = n.id where i.lots_primary_key = :lotID AND i.imei = :imie')
                    ,['lotID' => $is_exist->lots_primary_key, 'imie' => $request->get('imei')]);

                $amazon = DB::select(DB::raw('SELECT * FROM amazon_excel_files where model = :model AND network = :net AND storage = :stor
	                        AND color = :color AND category = :cat AND status = 1'),['model' => $data[0]->model, 'net' => $data[0]->network_name,
                            'stor' => $data[0]->storage_name, 'color' => $data[0]->color, 'cat' => $data[0]->name]);
                if (empty($amazon)) {
                    echo 'Empty';
                } else {
//                print_r($amazon[0]->id);die;
                    AmazonExcelFile::where('id', '=', $amazon[0]->id)
                        ->update([
                            'status' => 0,
                        ]);
                }
                if($issued_to == 'Refurbisher' ){
                $res = WarehouseInOut::insert([
                    'inventory_id' => $is_exist->id,
                    'issued_to' => $issued_to,
                    'color_folder' => $request->get('color_folder'),
                    'created_by' => Auth::id(),
                    'Account' => $request->Account,
                    ]);
                }else{
                     $res = WarehouseInOut::insert([
                    'inventory_id' => $is_exist->id,
                    'issued_to' => $issued_to,
                     'color_folder' => $request->get('color_folder'),
                    'created_by' => Auth::id(),
                    ]);
                }
                return redirect()->back()
                    ->with(['success_release' => 'Success: stocked out', 'issued_to' => $issued_to,'color_folder' => $request->get("color_folder"),'Account'=>$request->Account]);
                /*  return redirect()->route('warehouse.create',['issued_to'=>$issued_to])
                      ->with(['success' => 'Success: stocked out']);*/
            }
        }
        else{
            return redirect()->back()
                ->with(['fail_release' => 'Fail: Not available in "Warehouse"', 'issued_to' => $issued_to,'color_folder' => $request->get("color_folder"),'Account'=>$request->Account]);
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
        $products = WarehouseInOut::whereHas('inventory', function($query){
            $query->where('status','=',1);
        })->orderByDesc('id')
            ->simplePaginate(1);

        $products->total = WarehouseInOut::whereHas('inventory', function($query){
            $query->where('status','=',1);
        })->get()->count();

        return view('customer.warehouse.show',compact('products'));
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
    public function destroy(Request $request,$id)
    {
        if ($request->has('imei')) {
            $imei = $request->get('imei');

            $is_exist = Inventory::where('status', '=', 1)// available
            ->where('imei', '=', $imei)->first();
            if ($is_exist) {
                $already_exist_in = WarehouseInOut::where('inventory_id', '=', $is_exist->id)->first();
                if ($already_exist_in) {

                    $result = Inventory::where('id','=',$is_exist->id)
                        ->update([
                            'category_id'=> $request->get('category_id'),
                        ]);

                    $res = WarehouseInOut::find($already_exist_in->id)->delete();
                    $unmarry_msg = '';
                    if ($request->get('category_id') == 9 or $request->get('category_id') == 15) { // category id in problem or board
                        $unmarry = AttachIMEIToLCD::where('inventory_id', '=', $is_exist->id)
                            ->update([
                                'status' => 0 // mean unmarry
                            ]);
                        if ($unmarry) {
                            $unmarry_msg = ' And Detached Successfully';
                        }
                    }
                $success = 'Success: This ' . $imei . ' added back in warehouse. '.$unmarry_msg.'';
                $id =  $request->get('category_id');
                return redirect()->back()->with(compact('success','id'));

                } else {
                $fail = 'Fail: Not Check out from "Warehouse"';
                $id =  $request->get('category_id');
                return redirect()->back()->with(compact('fail','id'));

                }
            } else {
                // dd($request->get('category_id'));
                $fail = 'Fail: Not available in "Warehouse"';
                $id =  $request->get('category_id');
                return redirect()->back()->with(compact('fail','id'));
            }
        }
        else{

            $res = WarehouseInOut::find($id)->delete();
             $deleted = 'Success: record has been deleted';
                $id =  $request->get('category_id');
                return redirect()->back()->with(compact('deleted','id'));

        }
    }

    public function redFlag(Request $request){
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
                    $products = DB::select(DB::raw('SELECT w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.issued_to = :it AND w.created_at BETWEEN :from AND :to  AND l.color = :color order by w.id DESC '),
                        ['it' => $issued_to, 'from' => $from, 'to' => $to, 'color' => $color]);
                } else {
                    $products = DB::select(DB::raw('SELECT w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.issued_to = :it AND w.created_at BETWEEN :from AND :to order by w.id DESC '), ['it' => $issued_to, 'from' => $from, 'to' => $to]);
                }
            }
            else {
                $issued_to = $request->get('issued_to_for_report');
                $products = DB::select(DB::raw('SELECT w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
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

            $products =  DB::select(DB::raw('SELECT w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND w.created_at BETWEEN :from AND :to order by w.id DESC '), ['from' => $from, 'to' => $to]);

        }
        elseif ($request->has('colors')){
            $color = $request->get('colors');
            $products = DB::select(DB::raw('SELECT w.color_folder, l.model as model, s.name as storage, l.color as color, i.imei as imei, c.name as cat_name, w.issued_to, w.created_at as c_date
                        FROM warehouse_in_out w INNER JOIN inventories i on w.inventory_id = i.id
	                    INNER JOIN lots l on l.id = i.lots_primary_key INNER JOIN users u on u.id = i.created_by
                        INNER JOIN storages s on s.id = l.storage_id INNER JOIN categories c on c.id = i.category_id where i.status = 1
                        AND l.color = :color order by w.id DESC '), ['color' => $color]);
        }
        else{
            $products = [];
        }
//        print_r($products[0]);die;

       /* $expect_three_days =  strtotime(\Carbon\Carbon::now());
        $expect_three_days = strtotime("-3 day", $expect_three_days);
        $expect_three_days = date("Y-m-d", $expect_three_days);
        $redflag = WarehouseInOut::whereHas('inventory', function($query){
            $query->where('status','=',1);
        })->where('created_at','<', $expect_three_days)->get();*/
        return view('customer.warehouse.redflag',compact('products'));
    }

    public function warehouse_in_out(Request $request){
    // dd($request);
        if ($request->method() == 'POST') {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            $path = Storage::disk('public')->path($path);
            if ( $xlsx = SimpleXLSX::parse($path)) {

                foreach ($xlsx->rows() as $i => $elt) {
                    if ($i != 0) {
                       $excel = new AmazonExcelFile();
                       $excel->model = $elt[0];
                       $excel->network = $elt[1];
                       $excel->storage = $elt[2];
                       $excel->color = $elt[3];
                       $excel->category = $elt[4];
                       $excel->status = true;
                       $excel->save();
                    }
                }
            } else {
                    echo SimpleXLSX::parseError();
                }
            $products_release = AmazonExcelFile::where('status', '=', 1)->orderBy('id','DESC')->get();
            $products_release->total = AmazonExcelFile::where('status', '=', 1)->get()->count();
            $user = DB::table("users")->where('account_type','refurbishing')->get();
            $products_received = WarehouseInOut::whereHas('inventory', function ($query) {
                $query->where('status', '=', 1);
            })->orderByDesc('id')
                ->simplePaginate(3);


            $products_received->total = WarehouseInOut::whereHas('inventory', function ($query) {
                $query->where('status', '=', 1);
            })->get()->count();

            return redirect()->back()->with(compact('products_release','products_received','user'));

        }

        $products_release = AmazonExcelFile::where('status', '=', 1)->orderBy('id','DESC')->get();
//        print_r($products_release);die;
        $products_release->total = AmazonExcelFile::where('status', '=', 1)->get()->count();

        /*$products_release = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
            })->orderByDesc('id')
            ->simplePaginate(3);

        $products_release->total = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
        })->get()->count();*/

        $user = DB::table("users")->where('account_type','refurbishing')->get();
        $products_received = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
        })->orderByDesc('id')
            ->simplePaginate(3);


        $products_received->total = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
        })->get()->count();
//        return redirect()->back()->with(compact('products_release','products_received','user'));
        return view('customer.warehouse.in_out', compact('products_release','products_received','user'));

    }

        public function imei(Request $request){
                    return view('customer.warehouse.imei');

        }


        public function getimei(Request $request){
            $columns = array(
                            0 =>'id',
                            1 =>'imei',
                            2=> 'code',

                        );

        $totalData = DB::table('imei')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');

        $start = $request->input('start');

        $order = $columns[$request->input('order.0.column')];

        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {
            $posts = DB::table('imei')->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DB::table('imei')->where('imei','LIKE',"%{$search}%")
                             ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = DB::table('imei')->where('imei','LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['id'] = $post->id;
                $nestedData['imei'] = $post->imei;
                $nestedData['code'] = $post->code;
                   $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);
        }

        public function storeimei(Request $r)
    {
        $rules = [

            'file' => 'required',
        ];
        $customMessages = [
            'file.required' => 'Select File .',



        ];

        $this->validate($r, $rules, $customMessages);

                DB::beginTransaction();

        try{

        $extension = $r->file('file')->getClientOriginalExtension();

        //filename to store
        $filenametostore = time().'.'.$extension;


        $imageName = 'stores/'.$filenametostore;

        $image = $r->file('file');
        $image = Storage::disk('local')->put($imageName, file_get_contents($image), 'public');
        $path= Storage::disk('local')->url($imageName);

        $file=fopen(storage_path().'/app/'.$imageName,'r');


        $url = Storage::url( $path);
       // return $url;
       // $file=fopen($path,'r');
        while(! feof($file))  {
            $result = fgets($file);
if($result != null) {
    $line = explode(",", $result);
    $imei=$line[0];
    $code=$line[1];
  //  print_r($line);
    //echo $line[0] . '----' . $line[1];
    $check=DB::table('imei')->where('imei',$imei)->get();
    if(!count($check)>0)
    {
        DB::table('imei')->insert(['imei'=>$imei,'code'=>$code]);
    }

 //   echo '<br>';




}
        }
        fclose($file);
         DB::commit();

                       return redirect()->back()->withSuccess('File Uploaded Succssfully');

        }   catch (\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withErrors('Something went wrong, Please Try Again');

        }
    }

}
