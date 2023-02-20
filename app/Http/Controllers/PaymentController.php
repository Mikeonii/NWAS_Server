<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Payment;
use App\Models\Invoice;
class PaymentController extends Controller
{   
    public function destroy($payment_id){
       return Payment::where('id', $payment_id)->delete();
    }

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

    public function store(Request $request){
        $new = $request->isMethod('put') ? Payment::findOrFail($request->id) : new Payment;
        $new->invoice_id = $request->input('invoice_id');
        $new->amount_paid = $request->input('amount_paid');
        $new->payment_method = $request->input('payment_method');
        $new->payment_date = $request->input('payment_date');
        try{
            $new->save();
            if($request->isMethod('post')){
                $new = Invoice::findOrFail($request->input('invoice_id'));
                $new->invoice_status = $request->input('invoice_status');
                $new->save();

                $new = Invoice::where('id',$new->id)->with('payables.payable.warranty')->first();
                return $new;
            }else{
                return $new;
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
