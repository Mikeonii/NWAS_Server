<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Payable;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Expense;
use App\Models\Unit;
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
        
        // this function returns the summary of expense_amount, 
        // however we also need to consider the discounts given to the paid invoice which
        // is considered expense.
        $total_expense = $this->total_expense($month,$year);
        // total discount
        $total_discount = $this->total_discount($month,$year);
        // total balance 
        $total_balance = $this->total_balance($month,$year);
        // total item net
        $total_item_net = $this->total_item_net($month,$year);
        // total overall net 
        $total_overall_net = $total_item_net + $total_service_sales;

        $summary = collect([
            'Cash Received'=>$total_sales,
            'Total Item Net'=>$total_item_net,
            'Total Service Sales'=>$total_service_sales,
            'Total Expense'=>$total_expense,
            'Total Discount'=>$total_discount,
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
        $items_payables = Payable::whereHas('invoice',function($query) use($month,$year){
            return $query->where('invoice_status','Paid')
            ->whereMonth('updated_at',$month)
            ->whereYear('updated_at',$year);
        })->where('payable_type','App\Models\Item')
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
            $query->where('invoice_status','Paid');
            
        })->whereMonth('updated_at',$date[0])
        ->whereYear('updated_at',$date[1])->sum('amount');

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
        $total_discount = $this->total_discount($month,$year);
        $total_expense+=$total_discount;
        return $total_expense;
    }
    public function total_discount($month,$year){
        // get total amount discount where balance == 0
        $discount_given = Invoice::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('invoice_status','Paid')
        ->where('balance','==',0)
        ->sum('discount');
        return $discount_given;
    }
    public function total_balance($month,$year){
        $total_balance = Invoice::whereMonth('updated_at',$month)
                                  ->whereYear('updated_at',$year)
                                  ->where('is_quote',0)
                                  ->sum('balance');
        return $total_balance;
    }
    public function get_sales_summary(){
        $summary_data = collect();
        $today = Carbon::now();
        $start_of_week = Carbon::now()->startOfWeek();
        $end_of_week = Carbon::now()->endOfWeek();
        $start_of_month = Carbon::now()->startOfMonth();
    
        // get all item sales today
        $total_item_sales_today = $this->get_total_sales('Today')['item_sales'];
        $total_services_sales_today = $this->get_total_sales('Today')['service_sales'];
    
        // get all item sales this week
        $total_item_sales_this_week = $this->get_total_sales('This week')['item_sales'];
        $total_services_sales_this_week = $this->get_total_sales('This week')['service_sales'];
    
        // get all item sales this month
        $total_item_sales_this_month = $this->get_total_sales('This month')['item_sales'];
        $total_services_sales_this_month = $this->get_total_sales('This month')['service_sales'];
    
        // calculate expenses, net and cash on hand
        $total_expense_today = $this->get_total_expense('Today');
        $total_expense_this_week = $this->get_total_expense('This week');
        $total_expense_this_month = $this->get_total_expense('This month');
    
    
        $total_coh_today = $this->get_total_cash_on_hand('Today');
        $total_coh_this_week = $this->get_total_cash_on_hand('This week');
        $total_coh_this_month = $this->get_total_cash_on_hand('This month');
    
        // build the summary data collection
        $summary_data->push([
            'id'=>1,
            'label' => 'Today',
            'date' => $today->toFormattedDateString(),
            'data' => [
                'item_sales' => $total_item_sales_today,
                'services_sales' => $total_services_sales_today,
                'total_coh' => $total_coh_today,
                'total_expense' => $total_expense_today,
         
            ],
        ]);
    
        $summary_data->push([
            'id'=>2,
            'label' => 'This Week',
            'date' => $start_of_week->format('M d'). ' - '. $end_of_week->format('d'),
            'data' => [
                'item_sales' => $total_item_sales_this_week,
                'services_sales' => $total_services_sales_this_week,
                'total_coh' => $total_coh_this_week,
                'total_expense' => $total_expense_this_week,
         
            ],
        ]);
    
        $summary_data->push([
            'id'=>3,
            'label' => 'This Month',
            'date' => $start_of_month->format('F Y'),
            'data' => [
                'item_sales' => $total_item_sales_this_month,
                'services_sales' => $total_services_sales_this_month,
                'total_coh' => $total_coh_this_month,
                'total_expense' => $total_expense_this_month,
          
            ],
        ]);
    
        return $summary_data;
    }
    
    
    public function get_total_sales($type) {
        $item_sales = 0;
        $service_sales = 0;
        switch ($type) {
            case 'Today':
                $date = Carbon::today();
                $item_sales = Payable::where('payable_type', 'App\Models\Item')
                    ->whereDate('created_at', $date)
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                $service_sales = Payable::where('payable_type', 'App\Models\Service')
                    ->whereDate('created_at', $date)
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                break;
            case 'This week':
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $item_sales = Payable::where('payable_type', 'App\Models\Item')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                $service_sales = Payable::where('payable_type', 'App\Models\Service')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                break;
            case 'This month':
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
                $item_sales = Payable::where('payable_type', 'App\Models\Item')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                $service_sales = Payable::where('payable_type', 'App\Models\Service')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereHas('invoice', function($query) {
                        $query->where('invoice_status', 'Paid');
                    })
                    ->sum('amount');
                break;
            default:
                // handle unsupported $type values
                break;
        }
        return [
            'item_sales' => $item_sales,
            'service_sales' => $service_sales,
        ];
    }

    public function get_total_expense($type) {
        if ($type == 'Today') {
            $expense = Expense::whereDate('created_at', Carbon::now())->sum('expense_amount');
        } elseif ($type == 'This week') {
            $expense = Expense::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('expense_amount');
        } elseif ($type == 'This month') {
            $expense = Expense::whereMonth('created_at', Carbon::now()->month)->sum('expense_amount');
        } else {
            $expense = 0; // Default to 0 if type is not recognized
        }
    
        return $expense;
    }


    public function get_total_cash_on_hand($type) {
        $total_sales = $this->get_total_sales($type);
        $total_expense = $this->get_total_expense($type);
        $cash_on_hand = $total_sales['item_sales'] + $total_sales['service_sales'] - $total_expense;
        return $cash_on_hand;
    }
    
    

}
