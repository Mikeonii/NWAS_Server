<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Payment;
use App\Models\Invoice;
class PaymentController extends Controller
{   
    public function destroy($payment_id,$invoice_id){
        $payment = Payment::findOrFail($payment_id);
        $this->update_balance($invoice_id,$payment->amount_paid,'add');
        $payment->delete();
   
    }
    public function update_balance($invoice_id, $amount, $operation)
    {
        $new = Invoice::findOrFail($invoice_id);
        $new->balance += ($operation == 'subtract') ? (-$amount) : $amount;
        $new->invoice_status = ($new->balance == 0) ? 'Paid' : 'With Balance';
        $new->save();
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
            $this->update_balance($request->input('invoice_id'),$new->amount_paid,'subtract');
            $new = Invoice::where('id',$new->id)->with('payables.payable.warranty')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
