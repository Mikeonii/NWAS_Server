<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Exception;
class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function print_job_order_slip($data,$unit_id){
        $data = json_decode($data);
        $unit = Unit::where('id',$unit_id)->with('customer')->first();
        
        return view('print_job_order_slip')->with('data',$data)->with('unit',$unit);
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
        $unit->issued_warranty = json_encode($request->input('issued_warranty'));
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
