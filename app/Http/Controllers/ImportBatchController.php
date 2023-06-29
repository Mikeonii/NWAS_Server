<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportBatch;

class ImportBatchController extends Controller
{
    public function index(){
        return ImportBatch::with('supplier')->orderBy('batch_description', 'ASC')->get();
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
            self::check_for_break_even_date($importBatch);
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
    public static function check_for_break_even_date(ImportBatch $importBatch){
        // if capital == gross then set today as break even date
        if($importBatch->gross_amount == $importBatch->capital_amount){
            $today = Carbon::now();
            $importBatch->break_even_date = $today->format('Y-m-d');
            try{
                $imortBatch->save();
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        }
    }
    public static function modify_associated_units($operation,$quantity,$import_batch_id) {
        $import_batch = ImportBatch::findOrFail($import_batch_id);
        $no_of_units_associated = $import_batch->no_of_units_associated;
    
        if ($operation === 'add') {
            $import_batch->no_of_associated_units+=$quantity;
        } elseif ($operation === 'subtract') {
            $import_batch->no_of_associated_units-=$quantity;
        } else {
            return 'Invalid operation';
        }
        try {
            $import_batch->save();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
}
