<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $new= $request->isMethod('put') ? Problem::findOrFail($request->id) : new Problem; 
        $new->problem_description = strtoupper($request->input('problem_description'));
        $new->customer_id = $request->input('customer_id');
        $new->unit_id = $request->input('unit_id');
        $new->actions_performed = json_encode($request->input('actions_performed'));
        $new->recommendations = json_encode($request->input('recommendations'));
        $new->other_remarks = json_encode($request->input('other_remarks'));
        $new->technician = $request->input('technician');
        $new->repair_initialized = $request->input('repair_initialized');
        $new->status = $request->input('status');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function show($unit_id)
    {
        return Problem::where('unit_id',$unit_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Problem $problem)
    {
        return Problem::where('id',$problem)->destroy;
    }
}
