<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Payable;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Expense;
use DB;
class SummaryController extends Controller
{
    public function get_summary(){
        $month = date('m');
        $year = date('Y');
        // total sales
        $total_sales = $this->total_sales($month,$year);
        // total item sales
        $total_item_sales = $this->total_type_sales('items',$month,$year);
        // total service sales
        $total_service_sales = $this->total_type_sales('services',$month,$year);    
        // total expense
        $total_expense = $this->total_expense($month,$year);
        // total balance 
        $total_balance = $this->total_balance($month,$year);
        // total item net
        $total_item_net = $this->total_item_net($month,$year);
        // total overall net 
        $total_overall_net = $total_item_net + $total_service_sales;

        $summary = collect([
            'Total Sales'=>$total_sales,
            'Total Item Net'=>$total_item_net,
            'Total Service Sales'=>$total_service_sales,
            'Total Expense'=>$total_expense,
            'Total Collectibles'=>$total_balance
        ]);
        return $summary;
    }
    public function get_yearly_summary(){
        // get the total sales in service,items,over all net,and expense for the last 12 months.
        $months = collect(["January","Febuary","March","April","May","June","July","August","September","October","November","December"]);
        $year = date('Y');
        $summary = collect([]);
        $headings = collect(["Month","Item Sales","Service Sales","Expenses","Profit"]);
        $summary->push($headings);
        foreach($months as $index=>$month){

            $total_item_net = $this->total_item_net($index+1,$year);
            $total_service_sales = $this->total_type_sales('services',$index+1,$year);
            $total_overall_net = $total_item_net + $total_service_sales;
            $total_expense = $this->total_expense($index+1,$year);
            $total_profit = $total_overall_net - $total_expense;

            $sum = collect([$month,$total_item_net,$total_service_sales,$total_expense,$total_profit]);
            $summary->push($sum);
        }
        return $summary;
    }
    public function total_item_net($month,$year){
              /**total item net is total item sales - total item cost
         * in order to generate that, i need to get iinfo from payables table which follows:
         *  payable type == 'App\Models\items'
         *  payable_id,
         *  quantity,
         *  and get the item's selling price and unit price.
         *So there should be a direct relationship with payables and items table whic is payable.
         * */ 
        $items_payables = Payable::whereHas('invoice',function($query){
            return $query->where('invoice_status','Paid');
        })->where('payable_type','App\Models\Item')
        ->whereMonth('updated_at',$month)
        ->whereYear('updated_at',$year)
        ->with('payable')->get();
        $items_payables->map(function($item){
            // to get the item's capital, get the unit price*quantity
            // then amount - capital, that's your item's profit
            $capital = $item->payable->unit_price*$item->quantity;
            $item_profit = $item->amount - $capital;
            $item->item_profit = $item_profit;
        });

        return $items_payables->sum('item_profit');
    }
    public function total_type_sales($type,$month,$year){
        $selected_type = "";
        if($type=='items') $selected_type = 'App\Models\Item';
        if($type=='services') $selected_type = 'App\Models\Service';
        $date = collect([$month,$year]);
        $total_item_sales = Payable::where('payable_type',$selected_type)
        ->whereHas('invoice',function($query) use($date){
            return $query->where('invoice_status','Paid')
            ->whereMonth('invoice_date',$date[0])
            ->whereYear('invoice_date',$date[1]);
        })->sum('amount');

        return $total_item_sales;
    }
    public function total_sales($month,$year){
        $total_sales = Payment::whereMonth('payment_date', $month)
                                ->whereYear('payment_date', $year)
                                ->sum('amount_paid');
        return $total_sales;
    }
    public function total_expense($month,$year){
        $total_expense = Expense::whereMonth('date_paid', $month)
                                ->whereYear('date_paid', $year)    
                                ->sum('expense_amount');
        return $total_expense;
    }
    public function total_balance($month,$year){
        $total_balance = Invoice::whereMonth('updated_at',$month)
                                  ->whereYear('updated_at',$year)
                                  ->where('is_quote',0)
                                  ->sum('balance');
        return $total_balance;
    }
}
