<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Problem;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function print_work_history_slip($data,$unit_id){
        // check if data length is 2 or more.
        $data = json_decode($data);
        $is_two = null;
        if(sizeof($data) >=2 ){
            // two work history slip should be printed
            $is_two = true;
            return $is_two;
        }
        else{
            $problem = Problem::where('problem_description',$data[0])
            ->where('unit_id',$unit_id)->with('customer')->with('unit')->first();
            return view('print_work_history')->with('problem',$problem);
        }
    
    }
     public function print_job_order_slip($data,$unit_id){
        $data = json_decode($data);
        $unit = Unit::where('id',$unit_id)->with('customer')->first();
        
        return view('print_job_order_slip')->with('data',$data)->with('unit',$unit);
     }
        // get all units that has been picked up
        public function get_picked_up_units(){
            $now = Carbon::now();
            $last_week_start = $now->copy()->subWeek()->startOfWeek();
            $last_week_end = $now->copy()->subWeek()->endOfWeek();
            // $this_week_start = $now->startOfWeek();
            return Unit::whereNotNull('picked_up_date')
                       ->whereBetween('picked_up_date', [$last_week_start, $last_week_end])
                       ->with('customer')
                       ->with('problems')
                       ->orderBy('picked_up_date','DESC')
                       ->get();
        }
        
        
        
    public function index()
    {
        //
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
     * Store a newly created resource in storage.sd
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = $request->isMethod('put') ? Unit::findOrFail($request->id) : new Unit;
        $unit->customer_id = $request->input('customer_id');
        $unit->unit_type = $request->input('unit_type');
        $unit->unit_brand = $request->input('unit_brand') ? strtoupper($request->input('unit_brand')) : "";
        $unit->unit_model = $request->input('unit_model') ? strtoupper($request->input('unit_model')) :"";
        $unit->serial_no = $request->input('serial_no') ? strtoupper($request->input('serial_no')) :"";
        $unit->date_received = $request->input('date_received');
        $unit->picked_up_by = $request->input('picked_up_by');
        $unit->picked_up_date = $request->input('picked_up_date');
      
        $unit->includes = json_encode($request->includes);
       try{
           $unit->save();
           return $unit;
       }
       catch(Exception $e){
           return $e->getMessage();
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($customer_id)
    {
        return Unit::where('customer_id',$customer_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Unit::destroy($id);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
