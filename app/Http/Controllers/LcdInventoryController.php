<?php

namespace App\Http\Controllers;

use App\AttachIMEIToLCD;
use App\Brand;
use App\Category;
use App\Dispatch;
use App\DumyBarcode;
use App\Inventory;
use DB;
use App\LcdInventory;
use App\LcdIssuedTo;
use App\LcdIssuedToStatus;
use App\Lot;
use App\Returns;
use App\Testing;
use App\User;
use App\WarehouseInOut;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LcdInventoryController extends Controller
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
            $products = LcdInventory::orderByDesc('id')
                ->simplePaginate(30);
        }
         $proCount = LcdInventory::count();
        return view('customer.lcd_inventory.index',compact('products','brands','proCount'));

    }
    public function lcd_warehouse(Request $request)
    {
        $products = LcdInventory::orderByDesc('id')
            ->where('status','<>', '2') // dispatched
            ->simplePaginate(30);
        return view('customer.lcd_inventory.warehouse',compact('products'));

    }

    /**
     * Show the form for creating a new resource.$brand_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $brands = Brand::all();
//        $categories = Category::all();
        return view('customer.lcd_inventory.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LcdInventory::insert([
            'brand_id' => $request->brand_id,
            'modal' => $request->model,
            'category_id' => $request->category_id,
            'barcode' => $request->barcode,
            'status' => 1,  // 1 means available
            'created_by' => Auth::id()
        ]);
        DumyBarcode::where('barcode', '=', $request->barcode)->delete();

        return 1;
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
        /*$inventory = Inventory::find($id);
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
        return redirect()->back();*/
    }

    public function issue_lcd(Request $request){
        $status = $request->input('status');
        $issue_to = $request->input('issued_to');
        $barcode = $request->input('barcode');
        $assigned_to_account = $request->input('assigned_to_account');
        $category_id = $request->input('category_id');
        $color = $request->input('color');
        $receiver_name = $request->input('receiver_name');
        $lcd_inventory = LcdInventory::where('barcode','=',$barcode)->first();

        if ($request->input('status') == 1){
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3) {
                    return 'LCD Already Released';
                }else{
                    if ($lcd_inventory->status == 5) {
                        return 'Ooops! LCD not found!';
                    }
                    LcdInventory::where('id', '=', $lcd_inventory->id)
                        ->update([
                            'status' => 3,//released
                        ]);
                    $lcdIssuedToId = LcdIssuedTo::where('lcd_inventory_id', '=', $lcd_inventory->id)
                        ->update([
                            'status' => 3, //released 3
                            'assigned_to' => $issue_to,
                            'assigned_to_account' => $assigned_to_account,
                            'assigned_by' => \auth()->user()->id,
                        ]);
                    return 'LCD Released';
                }
            }
            else{
                return 'Ooops! LCD not found!';
            }
        }
        elseif ($request->input('status') == 2) // receive
        {
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3){
                    if ($lcd_inventory->issued->assigned_to == 'Phone_Refurbished'){
                        LcdInventory::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 4, //received
                            ]);
                        LcdIssuedTo::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 4, //received 4
                                'assigned_by' => \auth()->user()->id,
                            ]);
                        return 'LCD Received';
                    }else{
                        if ($category_id){
                            LcdInventory::where('id', '=', $lcd_inventory->id)
                                ->update([
                                    'status' => 4,
                                    'color' => $color,
                                    'category_id' => $category_id,
                                ]);
                            LcdIssuedTo::where('id', '=', $lcd_inventory->id)
                                ->update([
                                    'status' => 4, //received 4
                                    'assigned_by' => \auth()->user()->id,
                                ]);
                            return 'LCD Received';
                        }else{
                            return 'show_cat_and_color';
                        }
                    }
                }else{
                    return 'LCD not released';
                }
            }else {
                return 'Ooops! LCD not found!';
            }
        }
        elseif ($request->input('status') == 3) // Broken
        {
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3){
                    if ($request->input('reason')){
                        LcdIssuedTo::where('id','=', $lcd_inventory->id)
                            ->update([
                                'status' => 5, //broken 5
                                'reason' => $request->input('reason'),
                                'receiver_name' => $receiver_name,
                                'assigned_by' => \auth()->user()->id,
                            ]);
                        LcdInventory::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 5,
                            ]);
                        return 'LCD Added Into Broken list';
                    }else{
                        if ($lcd_inventory->issued->assigned_to == 'Phone_Refurbished'){
                            $user = User::find($lcd_inventory->issued->assigned_to_account);
                            return $data=[
                                'assigned_to' => $user->id,
                                'assigned_to_name' => $user->name,
                            ];
                        }else{
                            return 'Rainel';
                        }
                    }
                }
                else{
                    return 'LCD not released';
                }
            }
            else{
                return 'Ooops! LCD not found!';
            }
        }

        return view('customer.lcd_inventory.warehouse_lcd_assigned', compact('lcd_inventory'))->withInput($request->except('_token'));
    }
    public function broken_lcd(Request $request){
        $status = $request->input('status');
        $issue_to = $request->input('issued_to');
        $barcode = $request->input('barcode');
        $assigned_to_account = $request->input('assigned_to_account');
        $category_id = $request->input('category_id');
        $color = $request->input('color');
        $receiver_name = $request->input('receiver_name');
        $lcd_inventory = LcdInventory::where('barcode','=',$barcode)->first();

        if ($request->input('status') == 1){
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3) {
                    return 'LCD Already Released';
                }else{
                    if ($lcd_inventory->status == 5) {
                        return 'Ooops! LCD not found!';
                    }
                    LcdInventory::where('id', '=', $lcd_inventory->id)
                        ->update([
                            'status' => 3,//released
                        ]);
                    $lcdIssuedToId = LcdIssuedTo::where('lcd_inventory_id', '=', $lcd_inventory->id)
                        ->update([
                            'status' => 3, //released 3
                            'assigned_to' => $issue_to,
                            'assigned_to_account' => $assigned_to_account,
                            'assigned_by' => \auth()->user()->id,
                        ]);
                    return 'LCD Released';
                }
            }
            else{
                return 'Ooops! LCD not found!';
            }
        }
        elseif ($request->input('status') == 2) // receive
        {
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3){
                    if ($lcd_inventory->issued->assigned_to == 'Phone_Refurbished'){
                        LcdInventory::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 4, //received
                            ]);
                        LcdIssuedTo::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 4, //received 4
                                'assigned_by' => \auth()->user()->id,
                            ]);
                        return 'LCD Received';
                    }else{
                        if ($category_id){
                            LcdInventory::where('id', '=', $lcd_inventory->id)
                                ->update([
                                    'status' => 4,
                                    'color' => $color,
                                    'category_id' => $category_id,
                                ]);
                            LcdIssuedTo::where('id', '=', $lcd_inventory->id)
                                ->update([
                                    'status' => 4, //received 4
                                    'assigned_by' => \auth()->user()->id,
                                ]);
                            return 'LCD Received';
                        }else{
                            return 'show_cat_and_color';
                        }
                    }
                }else{
                    return 'LCD not released';
                }
            }else {
                return 'Ooops! LCD not found!';
            }
        }
        elseif ($request->input('status') == 3) // Broken
        {
            if ($lcd_inventory) {
                if ($lcd_inventory->status == 3){
                    if ($request->input('reason')){
                        LcdIssuedTo::where('id','=', $lcd_inventory->id)
                            ->update([
                                'status' => 5, //broken 5
                                'reason' => $request->input('reason'),
                                'receiver_name' => $receiver_name,
                                'assigned_by' => \auth()->user()->id,
                            ]);
                        LcdInventory::where('id', '=', $lcd_inventory->id)
                            ->update([
                                'status' => 5,
                            ]);
                        return 'LCD Added Into Broken list';
                    }else{
                        if ($lcd_inventory->issued->assigned_to == 'Phone_Refurbished'){
                            $user = User::find($lcd_inventory->issued->assigned_to_account);
                            return $data=[
                                'assigned_to' => $user->id,
                                'assigned_to_name' => $user->name,
                            ];
                        }else{
                            return 'Rainel';
                        }
                    }
                }
                else{
                    return 'LCD not released';
                }
            }
            else{
                return 'Ooops! LCD not found!';
            }
        }

        return view('customer.lcd_inventory.broken_lcd', compact('lcd_inventory'))->withInput($request->except('_token'));
    }

    public function check_barcode_is_exist(Request $request){

        $incomming_barcode = $request->get('barcode');
        $barcode = DumyBarcode::where('barcode' ,'=', $incomming_barcode)
            ->where('status' ,'=', 1)->first();
//        $barcode = LcdInventory::where('barcode' ,'=', $incomming_barcode)->first();

        if ($barcode)
        {
            $category = Category::where('name','=', $barcode->category)->first();
            return $data =[
                'brand_id' => $barcode->brand_id,
                'brand_name' => $barcode->brand->name,
                'modal' => $barcode->modal,
                'category' => $category->name,
                'category_id' => $category->id,
            ];
        }
        else
        {
            return 0;
        }
    }

    public function lcd_profile(Request $request){
        return view('customer.lcd_inventory.lcd_profile');
    }
    public function phone_profile(Request $request){
 
         $products_release = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
        })->orderByDesc('id')
            ->get();
           
        return view('customer.lcd_inventory.phone_profile',compact('products_release'));
    }
    public function phone_release_by_tester(Request $request){
 
         $products_release = WarehouseInOut::whereHas('inventory', function ($query) {
            $query->where('status', '=', 1);
        })->orderByDesc('id')
            ->get();
           
        return view('customer.lcd_inventory.phone_release_by_tester',compact('products_release'));
    }
    public function return_to_admin(Request $request){
        return view('customer.lcd_inventory.returne_to_admin');
    }
    public function attach_imei_with_lcd(Request $request){

        if ($request->isMethod('GET')) {
            if ($request->input('imei')) {
                $is_exist = Inventory::where('imei', '=', $request->input('imei'))->first();
                if ($is_exist){
                    return  $res = WarehouseInOut::where('inventory_id','=',$is_exist->id)->where('Account','=',auth()->user()->name)->first();
                }
                else {
                    return $is_exist;
                }
            }
            elseif($request->input('lcd_barcode')) {
                $lcdInventory = LcdInventory::where('barcode', '=', $request->input('lcd_barcode'))
                    ->where('status', '=', 3)->first();
                if ($lcdInventory){
                    $is_lcd_assigned =  $lcdInventory->issued()->where('assigned_to_account','=',\auth()->user()->id)->first();
                    if ($is_lcd_assigned){
                        return 'ok';
                    }
                    return 'not found';
                }
                return 'not found';
            }
            elseif ($request->input('attach_imei_to_lcd_id')){
                $id=  AttachIMEIToLCD::where('id','=',$request->input('attach_imei_to_lcd_id'))->first();
                WarehouseInOut::where('inventory_id', '=', $id->inventory_id)
                    ->update([
                        'acc_status' => '0'
                    ]);
                AttachIMEIToLCD::where('id','=',$request->input('attach_imei_to_lcd_id'))->delete();
               
                return redirect()->back();
            }
            return view('customer.lcd_inventory.attach_imei_with_lcd');
        }
        elseif ($request->isMethod('POST')){
            $inventory = Inventory::where('imei', '=', $request->input('imei'))->first();
            $lot_id = $inventory->lots_primary_key;
            $lcdInventory = LcdInventory::where('barcode', '=', $request->input('lcd_barcode'))->first();
            $lcd_modal = $lcdInventory->modal;
            $lot = Lot::find($lot_id);
            
            if ($lcdInventory) {
                Inventory::where('id', '=', $inventory->id)
                    ->update([
                        'category_id' => $lcdInventory->category_id
                    ]);
                    WarehouseInOut::where('inventory_id', '=', $inventory->id)
                    ->update([
                        'acc_status' => '1'
                    ]);
                AttachIMEIToLCD::insert([
                    'inventory_id' => $inventory->id,
                    'lcd_inventory_id' => $lcdInventory->id,
                    'created_by' => Auth::id(),
                    'status' => 1,
                ]);
            }else{
                return redirect()->back()->with(['success' => 'LCD not found!']);
            }
            return redirect()->back()->with(['success' => 'Record has been submitted']);
        }
    }





    public function print_barcode(Request $request)
    {
        if ($request->isMethod('GET')){
            return view('customer.lcd_inventory.print_barcode');
        }
        elseif ($request->isMethod('POST')){

        }
    }
    public function barcode_generator(Request $request){
        return view('customer.lcd_inventory.generate_barcode');
    }

    public function barcode(Request $request)
    {
// For demonstration purposes, get pararameters that are passed in through $_GET or set to the default value
        $filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
        $text = (isset($_GET["text"])?$_GET["text"]:"0");
        $size = (isset($_GET["size"])?$_GET["size"]:"20");
        $orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
        $code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
        $print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
        $sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

// This function call can be copied into your project and can be made from anywhere in your code
        $this->barcodePrinter( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );


    }

    private  function barcodePrinter( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor=1 ) {
        $code_string = "";
        // Translate the $text into barcode the correct $code_type
        if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211214" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code128a" ) {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211412" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code39" ) {
            $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
            }

            $code_string = "1211212111" . $code_string . "121121211";
        } elseif ( strtolower($code_type) == "code25" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0");
            $code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

            for ( $X = 1; $X <= strlen($text); $X++ ) {
                for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
                    if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
                        $temp[$X] = $code_array2[$Y];
                }
            }

            for ( $X=1; $X<=strlen($text); $X+=2 ) {
                if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
                    $temp1 = explode( "-", $temp[$X] );
                    $temp2 = explode( "-", $temp[($X + 1)] );
                    for ( $Y = 0; $Y < count($temp1); $Y++ )
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                }
            }

            $code_string = "1111" . $code_string . "311";
        } elseif ( strtolower($code_type) == "codabar" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
            $code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
                    if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
                        $code_string .= $code_array2[$Y] . "1";
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }

        // Pad the edges of the barcode
        $code_length = 20;
        if ($print) {
            $text_height = 30;
        } else {
            $text_height = 0;
        }

        for ( $i=1; $i <= strlen($code_string); $i++ ){
            $code_length = $code_length + (integer)(substr($code_string,($i-1),1));
        }

        if ( strtolower($orientation) == "horizontal" ) {
            $img_width = $code_length*$SizeFactor;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length*$SizeFactor;
        }

        $image = imagecreate($img_width, $img_height + $text_height);
        $black = imagecolorallocate ($image, 0, 0, 0);
        $white = imagecolorallocate ($image, 255, 255, 255);

        imagefill( $image, 0, 0, $white );
        if ( $print ) {
            imagestring($image, 5, 31, $img_height, $text, $black );
        }

        $location = 10;
        for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
            $cur_size = $location + ( substr($code_string, ($position-1), 1) );
            if ( strtolower($orientation) == "horizontal" )
                imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
            else
                imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
            $location = $cur_size;
        }

        // Draw barcode to the screen or save in a file
        if ( $filepath=="" ) {
            header ('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);
        } else {
            imagepng($image,$filepath);
            imagedestroy($image);
        }
    }

}
