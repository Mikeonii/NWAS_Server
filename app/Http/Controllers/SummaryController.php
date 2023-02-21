<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Payable;
use App\Models\Invoice;
use App\Models\Service;
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
                                        ->sum('amount_paid');
        return $total_sales_this_month;
    }

    public function get_total_expenses_this_month(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $total_expenses_this_month = Expense::whereYear('date_paid', $year)
                                        ->whereMonth('date_paid', $month)
                                        ->sum('expense_amount');
        return $total_expenses_this_month;
    }

    public function get_service_sales(){
        $service_sales = 
    }
    public function get_total_net(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        
    $total_cash = Payment::whereMonth('payment_date',$month)->whereYear('payment_date',$year)->sum('amount_paid');
    $total_receivable_balances = Invoice::whereMonth('invoice_date',$month)->sum('balance');

    $col = collect(
        [
        'total_cash_received'=>$total_cash,
        'total_receivable_balances'=> $total_receivable_balances,

        ]);
    return $col;

    
    }

 
}
