<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;
class CustomerController extends Controller
{
    public function generate_customer_code(){
        $customers = Customer::all();
        $customers->map(function($customer){
            $customer->customer_code = $this->formatStringFromName($customer->customer_name);
            $customer->save();
        });
        return $customers;
    }
    public function get_customer_info($customer_code){
        // check if exist
        $customer = Customer::where('customer_code',$customer_code)
            ->with('units.problems')
            ->with(['invoices' => function ($query) {
                $query->where('is_quote', 0)
                      ->with('payments')
                      ->with('payables.payable');
            }])
            ->firstOrFail();
    
            return $customer;
    }
    
    public function index(){
        return Customer::all();
    }
    
    public function store(Request $request)
    {

         $new = $request->isMethod('put') ? Customer::findOrFail($request->id) : new Customer;
         $new->customer_name = strtoupper($request->input('customer_name'));
         $new->customer_contact_no = $request->input('customer_contact_no');
         $new->other_contact_platform = $request->input('other_contact_platform');
         $new->customer_municipality = $request->input('customer_municipality');
         $new->customer_barangay = $request->input('customer_barangay');
         $new->customer_purok = $request->input('customer_purok');
         $new->customer_province = $request->customer_province;
         $new->where_did_you_find_us = $request->input('where_did_you_find_us');
         $new->customer_code = $this->formatStringFromName($request->input('customer_name'));
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function formatStringFromName($customer_name) {
        $customer_initials = '';
        $words = explode(' ', $customer_name);
        foreach ($words as $word) {
          $customer_initials .= substr($word, 0, 1);
        }
      
        $current_date = date('d');
        $current_date=$current_date.rand(0,10);
      
        return strval(strtoupper($customer_initials).$current_date);
    }
    
      public function destroy($customer_id){
        return Customer::where('id',$customer_id)->delete();
    }
}
