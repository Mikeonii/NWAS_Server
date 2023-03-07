<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Supplier;
class SupplierController extends Controller
{
    public function index(){
        return Supplier::orderBy('supplier_name','ASC')->get();
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? Supplier::findOrFail($request->id) : new Supplier;
        $new->supplier_name = $request->input('supplier_name');
        $new->supplier_contact_no  =$request->input('supplier_contact_no');
        $new->supplier_address =$request->input('supplier_address');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
