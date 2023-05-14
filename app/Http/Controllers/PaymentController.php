<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Item;
use App\Http\Controllers\ImportBatchController;
class PaymentController extends Controller
{   
    public function destroy($payment_id,$invoice_id){
        $payment = Payment::findOrFail($payment_id);
        $this->update_balance($invoice_id,$payment->amount_paid,'add');
        $payment->delete();
   
    }
    public function update_balance($invoice_id, $amount, $operation, $invoice_status)
    {
        $new = Invoice::findOrFail($invoice_id);
        $new->balance += ($operation == 'subtract') ? (-$amount) : $amount;
        $new->invoice_status = $invoice_status;

        if($invoice_status =='Paid') $this->process_item_gross($invoice_id);
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
            $this->update_balance($request->input('invoice_id'),$new->amount_paid,'subtract',$request->input('invoice_status'));
            $new = Invoice::where('id',$new->invoice_id)->with('payables.payable.warranty')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function process_item_gross($invoice_id){
        /**psuedocode
         * 1. if invoice is paid
         * 2. search through payables where invoice==invoice_id
         * 3. foreach payable, check if payable_type =='App\Models\Item'
         * 4. if true{ 1. get item->import_batch_id 2. update gross of batch item 3. get amount from payable row}
         * 5. else {return true}
         * */ 
      
        $invoice = Invoice::select('id')->where('id',$invoice_id)->with('payables')->first();
   
        foreach($invoice->payables as $payable){
            if($payable->payable_type == 'App\Models\Item'){
                $item = Item::findOrFail($payable->payable_id);
                $import_batch_id = $item->import_batch_id;
                // update gross of batch item
                return ImportBatchController::addGrossAmount($import_batch_id,$payable->amount);
            }
        }
    }
}
