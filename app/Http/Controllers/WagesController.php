<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wage;
use Exception;
use App\Http\Controllers\ExpenseController;
use App\Models\Employee;
use Carbon\Carbon;
class WagesController extends Controller
{
    public function show($id){
        // show wages for an employee
        $employee_wages = Wage::where('employee_id',$id)->get();
        return $employee_wages;
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? Wage::findOrFail($request->id) : new Wage;
        $new->employee_id = $request->employee_id;
        $new->half = $request->half;
        $new->month = $request->month;
        $new->year = $request->year;
        $new->total_days_worked = $request->total_days_worked;
        $new->deductions = $request->deductions;
        $new->notes = $request->notes;
        $new->total_gross = $request->total_gross;
        $new->total_net = $request->total_net;
        try{
            $new->save();
            // insert into expenses
            $this->add_to_expense($new);
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    // TODO: This is a function that adds wages into expense
    private function add_to_expense($new){
        $employee = Employee::findOrFail($new->employee_id);
        $expense_description = $employee->name."-".$new->wage_type;
        $expense_amount = $new->total_net;
        $expense_type = "Wages";
        $expense_date_paid = Carbon::now();

        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $request->request->add([
            'expense_description' => $expense_description,
            'expense_amount' => $expense_amount,
            'expense_type' => $expense_type,
            'date_paid' => $expense_date_paid
        ]);
        try{
            ExpenseController::store($request);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
     
    }
}
