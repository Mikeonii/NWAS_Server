<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Item;
use App\Models\Payable;
use App\Models\Quoteable;
use App\Models\Payment;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\ItemController;
class InvoiceController extends Controller
{

    public function update_invoice_balance()
    {
        // get all invoices
        $invoices = Invoice::all();
        // loop through each invoice
        foreach ($invoices as $invoice) {
            // get the sum of payments for the current invoice
            $payments_sum = Payment::where('invoice_id', $invoice->id)->sum('amount_paid');
            try{
                if($payments_sum == 0){
                    $invoice->balance = $invoice->total_amount;
                }
                else{
                    // calculate the new balance
                    $new_balance = $invoice->total_amount - $payments_sum;
                    // set the new balance for the invoice
                    $invoice->balance = $new_balance;
                }
                // save the invoice
                $invoice->save();
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        }
    }
    public function get_unpaid_invoices(){
        $unpaid = Invoice::where('invoice_status', '!=', 'Paid')
        ->where('is_quote', 0)
        ->with('customer:id,customer_name')
        ->with('payables.payable.warranty')
        ->with('payments')
        ->with('quoteables.quoteable.warranty')
        ->orderBy('balance','DESC')
        ->get();
    
    return $unpaid;
    
    }
    
    public function print($type,$invoice_id,$display_price){
        if($type =='invoice'){
            $invoice = Invoice::where('id',$invoice_id)
                                ->with('payables.payable')
                                ->with('payments')
                                ->with('customer')
                                ->first();
            $due_date = new Carbon($invoice->invoice_date);
            $due_date->addDays(15)->format('Y-m-d');
            $invoice->due_date = $due_date;
            return view('print_invoice')->with('invoice',$invoice)->with('display_price',$display_price);
        }
        elseif($type =='quote'){
            $invoice = Invoice::where('id',$invoice_id)->with('quoteables.quoteable')->with('payments')->with('customer')->first();
            return view('print_quote')->with('invoice',$invoice);
        }
        return "print";
    }
    public function show($customer_id){
        return Invoice::where('customer_id',$customer_id)
        ->with('payables.payable.warranty')
        ->with('payables.payable.import_batch')
        ->with('payments')
        ->with('payables.item')
        ->with('quoteables.quoteable.warranty')
        ->get();
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
        $new->balance = $request->input('total_amount');    

        try{
            $new->save();
            // get service payables
            $this->get_payables($request->input('payables'),$new->id,$request->input('is_quote'));
            $new = Invoice::where('id',$new->id)
            ->with('payables.payable.warranty')
            ->with('quoteables.quoteable.warranty')
            ->first();
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
                $insert_into = "Item";
            }
            $this->insert_item(
                $payable["id"],
                $invoice_id,
                $insert_into,
                $is_quote,
                $quantity,
                $amount,
                'create'
            );
        }
    }
    # add payables to existing invoice.
    # this function is used in adding an item in payablesModal.vue
    public function add_payables_to_invoice(Request $request){
        $insert_into = $request->input('insert_into');
        $id = $request->input('id');
        $invoice_id = $request->input('invoice_id');
        $is_quote = $request->input('is_quote');
        $quantity = $request->input('quantity');
        $amount = $request->input('total_amount');
        try{
            $this->insert_item($id,$invoice_id,$insert_into,$is_quote,$quantity,$amount,'create');
            $this->update_invoice_payment($invoice_id,$amount,true);
            return Invoice::where('id',$invoice_id)
            ->with('payables.payable.warranty')
            ->with('payments')
            ->with('quoteables.quoteable.warranty')
            ->first();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function delete_payable_from_invoice($payable_id, $is_quote, $invoice_id)
    {
        $model = $is_quote ? Quoteable::class : Payable::class;
        try {
            $payable = $model::findOrFail($payable_id);
            $amount = $payable->amount;
            $this->update_invoice_payment($invoice_id, $amount,false);
            $payable->delete();
            return Invoice::where('id',$invoice_id)
            ->with('payables.payable.warranty')
            ->with('payments')
            ->with('quoteables.quoteable.warranty')
            ->first();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function update_invoice_payment($invoice_id, $total_amount, $is_add)
    {
        $invoice = Invoice::findOrFail($invoice_id);
        // check if there is payment or not. The purpose of this use case is, 
        //if it is a new quotation/invoice and the user forgot to insert the item, the balance will also add which should'nt
        // the balance will be only updated if a payment is inserted. 
        if ($is_add) {
            $invoice->total_amount += $total_amount;
            $invoice->amount += $total_amount;
            $invoice->balance += $total_amount;
        } else {
            $invoice->total_amount -= $total_amount;
            $invoice->amount -= $total_amount;
            $invoice->balance -= $total_amount;
        }
        try {
            $invoice->save();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function insert_item($id,$invoice_id,$insert_into,$is_quote,$quantity,$amount,$action){
        try{ 
            // checkk insert into
            if($insert_into == 'Service'){
                $service = Service::findOrFail($id);
                // check if quote or not
                if($is_quote == 1) {
                    $service
                    ->quoteable()
                    ->$action([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
                else {
                    $service
                    ->payable()
                    ->$action([
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
                    ->$action([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
                }
                else {
                    $item
                    ->payable()
                    ->$action([
                        "invoice_id"=>$invoice_id,
                        "quantity"=>$quantity,
                        "amount"=>$amount
                    ]);
  
                    // modify item quantity - remove
                    $req = collect(['item_id'=>$item->id,'quantity'=>$quantity,'action'=>'remove']);
                    ItemController::modify_stock($req);

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
        $date = date('ymd', strtotime($invoice_date));
        $x = "JMBC".$date.rand(10,100);
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
            $inv->payables()->delete();
        }
        return $inv->delete();
     
    }
}
