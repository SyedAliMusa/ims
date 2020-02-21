<?php

namespace App\Http\Controllers;

use App\Testing;
use App\Dispatch;
use App\Inventory;
use App\Lot;
use App\Models\Brand;
use App\Returns;
use App\Repairing;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("America/New_York");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d1 = date("Y-m-d",strtotime("-1 days"));
        $d2 = date("Y-m-d",strtotime("-2 days"));
        $d3 = date("Y-m-d",strtotime("-3 days"));
        $d4 = date("Y-m-d",strtotime("-4 days"));
        $d5 = date("Y-m-d",strtotime("-5 days"));
        $d6 = date("Y-m-d",strtotime("-6 days"));
        $d7 = date("Y-m-d",strtotime("-7 days"));
        
        $lot = Lot::count();
        $inventory = Inventory::count();
        $dispatch = Dispatch::where('status','0')->count();
        $dispatchR = Dispatch::where('status','1')->count();

        // kathy test 
        $testK = array();
        $testK['d1'] = Testing::where('created_by','23')->whereDate('created_at',$d1)->count();
        $testK['d2'] = Testing::where('created_by','23')->whereDate('created_at',$d2)->count();
        $testK['d3'] = Testing::where('created_by','23')->whereDate('created_at',$d3)->count();
        $testK['d4'] = Testing::where('created_by','23')->whereDate('created_at',$d4)->count();
        $testK['d5'] = Testing::where('created_by','23')->whereDate('created_at',$d5)->count();
        $testK['d6'] = Testing::where('created_by','23')->whereDate('created_at',$d6)->count();
        $testK['d7'] = Testing::where('created_by','23')->whereDate('created_at',$d7)->count();
        
        // Branda test 
        $testB = array();
        $testB['d1'] = Testing::where('created_by','24')->whereDate('created_at',$d1)->count();
        $testB['d2'] = Testing::where('created_by','24')->whereDate('created_at',$d2)->count();
        $testB['d3'] = Testing::where('created_by','24')->whereDate('created_at',$d3)->count();
        $testB['d4'] = Testing::where('created_by','24')->whereDate('created_at',$d4)->count();
        $testB['d5'] = Testing::where('created_by','24')->whereDate('created_at',$d5)->count();
        $testB['d6'] = Testing::where('created_by','24')->whereDate('created_at',$d6)->count();
        $testB['d7'] = Testing::where('created_by','24')->whereDate('created_at',$d7)->count();
       
        // Sindy Refurbisher 
        $refS = array();
        $refS['d1'] = Repairing::where('created_by','51')->whereDate('created_at',$d1)->count();
        $refS['d2'] = Repairing::where('created_by','51')->whereDate('created_at',$d2)->count();
        $refS['d3'] = Repairing::where('created_by','51')->whereDate('created_at',$d3)->count();
        $refS['d4'] = Repairing::where('created_by','51')->whereDate('created_at',$d4)->count();
        $refS['d5'] = Repairing::where('created_by','51')->whereDate('created_at',$d5)->count();
        $refS['d6'] = Repairing::where('created_by','51')->whereDate('created_at',$d6)->count();
        $refS['d7'] = Repairing::where('created_by','51')->whereDate('created_at',$d7)->count();
        
        
       
        // Fatima Refurbisher 
        $refF = array();
        $refF['d1'] = Repairing::where('created_by','51')->whereDate('created_at',$d1)->count();
        $refF['d2'] = Repairing::where('created_by','51')->whereDate('created_at',$d2)->count();
        $refF['d3'] = Repairing::where('created_by','51')->whereDate('created_at',$d3)->count();
        $refF['d4'] = Repairing::where('created_by','51')->whereDate('created_at',$d4)->count();
        $refF['d5'] = Repairing::where('created_by','51')->whereDate('created_at',$d5)->count();
        $refF['d6'] = Repairing::where('created_by','51')->whereDate('created_at',$d6)->count();
        $refF['d7'] = Repairing::where('created_by','51')->whereDate('created_at',$d7)->count();
        
        
        
        //   dd($test);
        return view('customer.home',compact('lot','inventory','dispatch','dispatchR','testK','testB','refS','refF'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        if ($request->has('q')) {

            $products = Inventory::whereHas('lot', function($query) use ($q){
                $query->where('model','=',$q);
                $query->orwhere('color','=',$q);
                $query->orwhere('asin','=',$q);
                $query->orwhere('lot_id','=',$q);
                $query->orwhere('imei','=',$q);
            })->get();

            $products->available = Inventory::whereHas('lot', function($query) use ($q){
                $query->where('model','=',$q);
                $query->orwhere('color','=',$q);
                $query->orwhere('asin','=',$q);
                $query->orwhere('lot_id','=',$q);
                $query->orwhere('imei','=',$q);
            })->where('status','=',1)->count();
        }
        else{
            $products = array();
        }
        return view('customer.search.index',compact('products'));
    }


    public function testMail()
    {

        /*$data = array('name'=>"Virat Gandhi");

        Mail::send(['text'=>'welcome'], $data, function($message) {
            $message->to('mahmoodch107@gmail.com', 'Mahmood')->subject
            ('Laravel Basic Testing Mail');
            $message->from('test@legharisons.com','mail sender');
        });
        echo "Basic Email Sent. Check your inbox.";*/

        return view('welcome');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function getUsers(Request $request)
    {
        $users = User::where('is_deleted','<>',1)
            ->get();
        return view('users_data',compact('users'));

    }
    public function deleteUsers(Request $request,$user_id)
    {
        $result = User::where('id','=',$user_id)
            ->update([
                'is_deleted'=> 1,
            ]);

        if ($result == 1){
            return redirect()->back()->with(['message'=>'record deleted successfully!']);
        }
        else{
            return redirect()->back()->with(['error'=>'Something wants wrong!']);

        }
    }
}
