<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;
class CustomerController extends Controller
{
    public function index(){
        return Customer::all();
    }
    
    public function store(Request $request)
    {
        $data = $request->only(['customer_name', 'customer_contact_no',
         'other_contact_platform', 'customer_municipality', 
         'customer_barangay', 'customer_purok', 'where_did_you_find_us']);

        if ($request->isMethod('post')) {
            $customer = new Customer($data);
        } else {
            $customer = Customer::findOrFail($request->id);
            $customer->fill($data);
        }

        try{
            $customer->save();
            return $customer;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
