<?php

namespace App\Http\Controllers;

use App\AttachIMEIToLCD;
use App\Dispatch;
use App\Inventory;
use App\LcdInventory;
use App\LcdIssuedTo;
use App\Testing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispatchController extends Controller
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
        $from =  strtotime(\Carbon\Carbon::now());
        $from = date("Y-m-d", $from);
        if ($request->has('query')){
            $term = $request->get('query');

            $products = Dispatch::whereHas('inventory', function($query) use ($term){
                $query->where('imei','=', $term);
                $query->orwhere('tracking','like', "%{$term}");
            })->orderByDesc('dispatches.created_at')
                ->simplePaginate(1000);

            /* $products = Dispatch::Where('tracking','like', "%{$request->get('query')}")
                 ->orWhere('imei','like', "%{$request->get('query')}")
                 ->orderByDesc('dispatches.created_at')
                 ->simplePaginate(1000);*/

            $products->total = 1;
        }
        else {
            $products = Dispatch::orderByDesc('id')->where('is_archived', '!=', true)->simplePaginate(20);
            $products->total = Dispatch::get()->count();
            $total_imei = \App\Dispatch::where('created_at','>', $from)->count();

        }
        return view('customer.dispatch.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('customer.dispatch.create');
        /*if ($request->has('addmore')){
            $imei = $request->get('imei');
            $tracking_id = 'addmore'.Auth::id();
            $inventory = Inventory::where('imei','=', $imei)->first();
            if ($inventory) {
//                $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
                $has_in_testing = true;
                if ($has_in_testing) {
                    $dispatch_insert = Dispatch::insert([
                        'inventory_id' => $inventory->id,
                        'tracking' => $tracking_id,
                        'created_by' => Auth::id(),
                        'is_archived' => true,
                    ]);
                    $res = Inventory::where('id','=',$inventory->id)
                        ->update([
                            'status' => 0,
                        ]);
                    $has_imei_to_lcd = AttachIMEIToLCD::where('inventory_id', '=', $inventory->id)->first();
                    if ($has_imei_to_lcd){
                        AttachIMEIToLCD::where('inventory_id', '=', $inventory->id)
                            ->update([
                                'status' => 2,
                            ]);
                        LcdInventory::where('id', '=', $has_imei_to_lcd->lcd_inventory_id)
                            ->update([
                                'status' => 2,
                            ]);
                    }
                    return 1;//success;
                }
                else{
                    return 2;//not in testing
                }
            }
            else{
                return 0; //not found
            }
        }
        elseif ($request->input('is_duplicate')){
            $inventory = Inventory::where('imei','=', $request->input('is_duplicate'))->first();
            if ($inventory){
                return 2;
            }else{
                return 1;
            }
        }
        else{
            $wronglyDispatched =  Dispatch::where('tracking','=', 'addmore'.\auth()->id())->get();
            if (count($wronglyDispatched) < 3){
                foreach ($wronglyDispatched as $wrongly){
                    Dispatch::where('id','=', $wrongly->id)->delete();
                }
            }
            return view('customer.dispatch.create');
        }*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $from =  strtotime(\Carbon\Carbon::now());
        $from = date("Y-m-d", $from);
        $data = $request->all();
        $track_id = $data['tracking_id'];
        $err = '';

        /* for($i = 0;$i < count($data['imies']); $i++) {
             if ($data['imies'][$i] = $track_id){
                 $err = 'MatchIds';
                return view('customer.dispatch.create');
             }
         }*/

        for($i = 0;$i < count($data['imies']); $i++) {
            $inventory = Inventory::where('imei','=', $data['imies'][$i])->first();
            $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
            $dispatch_insert = Dispatch::insert([
                'inventory_id' => $inventory->id,
                'tracking' => $track_id,
                'created_by' => Auth::id(),
            ]);
            $res = Inventory::where('id', '=', $inventory->id)
                ->update([
                    'status' => 0,
                ]);
            $has_imei_to_lcd = AttachIMEIToLCD::where('inventory_id', '=', $inventory->id)->first();
            if ($has_imei_to_lcd){
                LcdInventory::where('id', '=', $has_imei_to_lcd->lcd_inventory_id)
                    ->update([
                        'status' => 2,
                    ]);
            }
        }
        $imei_tracking=array();
        $total_imei = \App\Dispatch::where('created_at','>', $from)->count();
        $tracking_ids = \App\Dispatch::where('created_at','>', $from)->distinct('tracking')->count('tracking');
        array_push($imei_tracking,$total_imei,$tracking_ids,$err);
        return $imei_tracking;





        /*if ($request->has('enable_multi_imei')) {
            $tracking_id = $request->get('tracking_id');
            $res = Dispatch::where('tracking', '=', $request->get('enable_multi_imei'))
                ->update([
                    'tracking' => $tracking_id,
                    'is_archived' => false,
                ]);
            if ($request->has('imei')) {
                $imei = $request->get('imei');
                $tracking_id = $request->get('tracking_id');
                $inventory = Inventory::where('imei', '=', $imei)->first();
                if ($inventory) {
//                    $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
                    $has_in_testing = true;
                    if ($has_in_testing) {
                        $dispatch_insert = Dispatch::insert([
                            'inventory_id' => $inventory->id,
                            'tracking' => $tracking_id,
                            'created_by' => Auth::id(),
                            'is_archived' => false,
                        ]);
                        $res = Inventory::where('id', '=', $inventory->id)
                            ->update([
                                'status' => 0,
                            ]);
                        $has_imei_to_lcd = AttachIMEIToLCD::where('inventory_id', '=', $inventory->id)->first();
                        if ($has_imei_to_lcd){
                            LcdInventory::where('id', '=', $has_imei_to_lcd->lcd_inventory_id)
                                ->update([
                                    'status' => 2,
                                ]);
                            LcdIssuedTo::where('lcd_inventory_id','=', $has_imei_to_lcd->lcd_inventory_id)
                                ->update([
                                    'status' => 4, //dispatched
                                ]);
                        }
                        return 1;
                    } else {
                        return redirect()->back()->with(['imei_not_tested' => 'imei not tested']);
                    }
                } else {
                    return redirect()->back()->with(['error' => 'imei not found']);
                }
            }
            $imei_tracking=array();
            $total_imei = \App\Dispatch::where('created_at','>', $from)->count();
            $tracking_ids = \App\Dispatch::where('created_at','>', $from)->distinct('tracking')->count('tracking');
            array_push($imei_tracking,$total_imei,$tracking_ids);
            return $imei_tracking;

        }
        else if ($request->has('imei')) {
            $imei = $request->get('imei');
            $tracking_id = $request->get('tracking_id');
            $inventory = Inventory::where('imei', '=', $imei)->first();
            if ($inventory) {
//                $has_in_testing = Testing::where('inventory_id', '=', $inventory->id)->first();
                $has_in_testing = true;

                if ($has_in_testing) {
                    $dispatch_insert = Dispatch::insert([
                        'inventory_id' => $inventory->id,
                        'tracking' => $tracking_id,
                        'created_by' => Auth::id(),
                        'is_archived' => false,
                    ]);
                    $res = Inventory::where('id', '=', $inventory->id)
                        ->update([
                            'status' => 0,
                        ]);
                    $has_imei_to_lcd = AttachIMEIToLCD::where('inventory_id', '=', $inventory->id)->first();
                    if ($has_imei_to_lcd){
                        LcdInventory::where('id', '=', $has_imei_to_lcd->lcd_inventory_id)
                            ->update([
                                'status' => 2,
                            ]);
                    }
                    return redirect()->back()->with(['success' => 'Record has been dispatched']);
                } else {
                    return redirect()->back()->with(['imei_not_tested' => 'imei not tested']);
                }
            } else {
                return redirect()->back()->with(['error' => 'imei not found']);
            }
        }*/
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
        $dispatch = Dispatch::find($id);
        Inventory::where('id', '=', $dispatch->inventory_id)
            ->update([
                'status' => 1
            ]);
        $has_imei_to_lcd = AttachIMEIToLCD::where('inventory_id', '=', $dispatch->inventory_id)->first();
        if ($has_imei_to_lcd){
            AttachIMEIToLCD::where('inventory_id', '=',$dispatch->inventory_id)
                ->update([
                    'status' => 1,
                ]);
            LcdInventory::where('id', '=', $has_imei_to_lcd->lcd_inventory_id)
                ->update([
                    'status' => 3,
                ]);
        }
        $res = Dispatch::find($id)->delete();
        return redirect()->back()->with(['deleted' => 'One record has been deleted']);
    }
}
