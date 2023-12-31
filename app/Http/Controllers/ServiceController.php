<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Service;
class ServiceController extends Controller
{

    public function index(){
        return Service::with('warranty')->with('supplier')->orderBy('service_name','ASC')->get();
    }

    public function update(Request $request){
        $new = Service::findOrFail($request->id);
        $new->service_name = $request->input('service_name');
        $new->service_amount = $request->input('service_amount');
        $new->supplier_id = $request->input('supplier_id');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function store(Request $request){
        $new = $request->isMethod('put') ? Service::findOrFail($request->id) : new Service;
        $new->supplier_id = $request->input('supplier_id');
        $new->supplier_service_amount = $request->input('supplier_service_amount');
        $new->service_name = $request->input('service_name');
        $new->service_amount = $request->input('service_amount');
        $new->profitable_margin = $request->input('profitable_margin');
        try{
            $new->save();
            $warrantyData = [
                'warranty_count' => $request->input('warranty.warranty_count', $request->input('warranty_count')),
                'warranty_duration' => $request->input('warranty.warranty_duration', $request->input('warranty_duration')),
            ];
            
            if ($request->isMethod('put')) $new->warranty()->update($warrantyData);
            else $new->warranty()->create($warrantyData);
            
            $new = Service::where('id',$new->id)->with('warranty')->with('supplier')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function add_service_payment(Request $request){
        $customer_id = $request->input('customer_id');
        $service_id = $request->input('service_id');
        $payment_method = $request->input('payment_method');
        $discount = $request->input('discount');

        $service = Service::findOrFail($service_id);
        $amount = $service->service_amount;
        // return $service;
        $total_amount = $amount - $discount;
        $payment_date = $request->input('payment_date');
        try{
            $service->payment()->create([
                "customer_id"=>$customer_id,
                "discount"=>$discount,
                "total_amount"=>$total_amount,
                "payment_method"=>$payment_method,
                "payment_date"=>$payment_date
            ]);
            return $service;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
