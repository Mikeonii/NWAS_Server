<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Item;
use Carbon\Carbon;
class InvoiceController extends Controller
{
    public function print($type,$invoice_id){
    
        if($type =='invoice'){
            $invoice = Invoice::where('id',$invoice_id)->with('payables.payable')->with('customer')->first();
            $due_date = new Carbon($invoice->invoice_date);
            $due_date->addDays(15)->format('Y-m-d');
            $invoice->due_date = $due_date;
            return view('print_invoice')->with('invoice',$invoice);
        }
        elseif($type =='quote'){
            $invoice = Invoice::where('id',$invoice_id)->with('quoteables.quoteable')->with('customer')->first();
            return view('print_quote')->with('invoice',$invoice);
        }
        return "print";
    }
    public function show($customer_id){
        return Invoice::where('customer_id',$customer_id)->with('payables.payable.warranty')->with('quoteables.quoteable.warranty')->get();
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? Invoice::findOrFail($request->id) : new Invoice;
        $new->customer_id = $request->input('customer_id');
        $new->discount = $request->input('discount');
        $new->amount = $request->input('amount');
        $new->total_amount = $request->input('total_amount');
        $new->invoice_date = $request->input('invoice_date');
        $new->invoice_code = $this->generate_invoice_code($request->input('invoice_date'));
        $new->invoice_status = "Waiting for Payment";
        $new->is_quote = $request->input('is_quote');
        $purpose = $request->input('is_quote') == 1 ? strtoupper($request->input('purpose')) : "N/a";
        $new->purpose = $purpose;

        try{
            $new->save();
            // get service payables
            $this->get_payables($request->input('payables'),$new->id,$request->input('is_quote'));
            $new = Invoice::where('id',$new->id)->with('payables.payable.warranty')->with('quoteables.quoteable.warranty')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_payables($payables,$invoice_id,$is_quote){
        foreach($payables as $payable){
            $quantity = $payable["quantity"];
            $amount = $payable["total_amount"];
            // if service
            $insert_into = "";
            if(array_key_exists("service_name", $payable)){
                $insert_into = "Service";
            }
            // if item
            elseif(array_key_exists("item_name", $payable)){
                $insert_intp = "Item";
            }
            $this->insert_item(
                $payable["id"],
                $invoice_id,
                $insert_into,
                $is_quote,
                $quantity,
                $amount
            );
        }
    }
    public function insert_item($id,$invoice_id,$insert_into,$is_quote,$quantity,$amount){
        try{
            // checkk insert into
            if($insert_into == 'Service'){
                $service = Service::findOrFail($id);
                // check if quote or not
                if($is_quote == 1) {
                    $service
                    ->quoteable()
                    ->create([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
                else {
                    $service
                    ->payable()
                    ->create([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
            }
            else{
                $item = Item::findOrFail($id);
                 // check if quote or not
                 if($is_quote == 1) {
                    $item
                    ->quoteable()
                    ->create([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
                else {
                    $item
                    ->payable()
                    ->create([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function print_form($form,$invoice_id){
        $invoice = Invoice::where('id',$invoice_id)
        ->with('payables.payable.warranty')
        ->with('customer')
        ->first();

        if($form=='quotation'){
        }
        elseif($form=='invoice_with_payments'){
        }
        elseif($form=='invoice_with_laptop_problem_and_with_payments'){   
        }
    }
    public function generate_invoice_code($invoice_date){
        $x = "JMBC".$invoice_date.rand(10,100);
        return $x;
    }
    public function delete_invoice($invoice_id){
        // check if invoice or quote
        $inv = Invoice::findOrFail($invoice_id);
        if($inv->is_quote){
            // delete quotables
            $inv->quoteables()->delete();
        }
        else{
            // delete payables
            $inv->payables()->delete();
        }
        return $inv->delete();
     
    }
}
