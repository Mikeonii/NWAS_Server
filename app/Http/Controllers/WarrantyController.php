<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
class WarrantyController extends Controller
{
    public function edit(Request $request){
        $war = Warranty::findOrFail($request->id);
        $war->supplier_id = $request->input('supplier_id');
        $war->warranty_count = $request->input('warranty_count');
        $war->warranty_duration = $request->input('warranty_duration');
        try{
            $war->save();
            return $war;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
