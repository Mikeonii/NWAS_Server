<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportBatch;

class ImportBatchController extends Controller
{
    public function index(){
        return ImportBatch::with('supplier')->get();
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? ImportBatch::findOrFail($request->id) : new ImportBatch;
        $new->batch_description = strtoupper($request->input('batch_description'));
        $new->supplier_id = $request->input('supplier_id');
        $new->capital_amount = $request->input('capital_amount');
        $new->date_ordered = $request->input('date_ordered');
        $new->date_arrived = $request->input('date_arrived');
        if($request->isMethod('post')){
            $new->gross_amount = 0;
            $new->net_amount -=$new->capital_amount;
        }
       
        try{
            $new->save();
            $new->load('supplier');
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public static function addGrossAmount($id, $amount)
    {
        $importBatch = ImportBatch::findOrFail($id);
        $importBatch->gross_amount += $amount;
        try {
            self::calculateNetAmount($importBatch);
            $importBatch->save();
            return $importBatch;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public static function calculateNetAmount(ImportBatch $importBatch)
    {
        $totalNet = $importBatch->gross_amount - $importBatch->capital_amount;
        $importBatch->net_amount = $totalNet;
        try {
            $importBatch->save();
            return $importBatch;
        } catch (Exception $e) {
            return $e->getMessage;
        }
    }
}
