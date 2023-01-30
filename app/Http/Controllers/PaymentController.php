<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Payment;
class PaymentController extends Controller
{
    public function update(Request $request){
        $new = Payment::findOrFail($request->id);
        $new->customer_id = $request->input('customer_id');
        $new->amount = $request->input('amount');
        $new->payment_method = $request->input('payment_method');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function show($customer_id){
        $payments = Payment::where('customer_id',$customer_id)->with('paymentable.warranty')->get();
        return $payments;
    }


}
