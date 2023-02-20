<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Payable;
use DB;
class SummaryController extends Controller
{
    public function summary(){

        

    }

    public function get_total_sales_this_month(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $total_sales_this_month = Payment::whereYear('payment_date', $year)
                                        ->whereMonth('payment_date', $month)
                                        ->sum('amount');
        return $total_sales_this_month;
    }

    public function get_total_expenses_this_month(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $total_expenses_this_month = Expense::whereYear('date_paid', $year)
                                        ->whereMonth('date_paid', $month)
                                        ->sum('amount');
        return $total_expenses_this_month;
    }

    public function get_total_net(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        
    // get payables where in invoice's invoice_status == 'Paid'
    $total_paid_cash = Payable::join('invoices', 'payables.invoice_id', '=', 'invoices.id')
    ->where('invoices.invoice_status', '=', 'Paid')
    ->whereMonth('invoices.updated_at',$month)
    ->whereYear('invoices.updated_at',$year)
    ->select('payables.*')
    ->get();   
    
    $total_cash = Payment::whereMonth('payment_date',$month)->whereYear('payment_date',$year)->get();

    $total_cash_receivables = Payable::join('invoices', 'payables.invoice_id', '=', 'invoices.id')
    ->where('invoices.invoice_status', '=', 'With Balance')
    ->whereMonth('invoices.updated_at',$month)
    ->whereYear('invoices.updated_at',$year)
    ->select('payables.*')
    ->get(); 
    
    $col = collect(
        [
        'total_cash'=>$total_cash->sum('amount_paid'),
        'total_paid_cash'=>$total_paid_cash->sum('amount'),
        'total_with_balance_cash'=>$total_cash_receivables->sum('amount'),
        'total_waiting_for_payment_cash'=> $total_waiting_for_payment_cash->sum('amount')
        ]);
    return $col;

    
    }

 
}
