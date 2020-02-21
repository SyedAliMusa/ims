<?php

namespace App\Http\Controllers;

use App\Category;
use App\Inventory;
use App\Problems;
use App\Repairing;
use App\RepairingProblem;
use App\Returns;
use App\Testing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairingController extends Controller
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
        $products = Repairing::orderByDesc('id')->simplePaginate(50);
        return view('customer.repairing.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('customer.repairing.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $problems = $request->except(['_token', 'imei']);

        $imei = $request->get('imei');
        $inventory = Inventory::where('imei', '=', $imei)->first();
        if ($inventory) {
            if (count($problems)) {
                $repairing_id = Repairing::insertGetId([
                    'inventory_id' => $inventory->id,
                    'created_by' => Auth::id(),
                ]);
                $count = 0;
                foreach ($problems as $key => $value) {

                    $result = RepairingProblem::insert([
                        'repairing_id' => $repairing_id,
                        'problem' => $key,
                        'problem_name' => $problems[$key],
                    ]);
                    $count++;
                }
                return redirect()->back()->with(['message' => 'Record has been added.']);
            }else{
                return redirect()->back()->with(['message' => 'Testing point not selected']);
            }
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($testing_id)
    {

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
