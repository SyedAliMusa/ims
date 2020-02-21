<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPermissions;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $products = User::where('is_deleted','=', 0)->paginate(100);
        return view('customer.users.index',compact('products'));
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
        if ($request->has('set_permission')){
            $Privileges = $request->get('Privileges');
            $pri_arr = array();
            foreach ($Privileges as $privilege){
               $privil_data  = ['u_id'=>$id, 'p_id'=>$privilege];
                array_push($pri_arr, $privil_data);
            }
            UserPermissions::where('u_id','=',$id)->delete();
            UserPermissions::insert($pri_arr);
            return redirect()->back()->with(['permission_approved'=> 'User permissions has been updated']);
        }
        $user = User::find($id);
        return view('customer.users.show',compact('user'));
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
        $result = User::where('id','=',$id)
            ->update([
                'password' => bcrypt($request->get('password')),
            ]);
        return redirect()->back()->with(['success'=> 'Record has been updated']);
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
