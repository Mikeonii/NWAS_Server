<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Wage;
use Exception;

class EmployeeController extends Controller
{
    public function index(){
        return Employee::all();
    }

    public function store(Request $request){
        $new = $request->isMethod('put') ? Employee::findOrFail($request->id) : new Employee;
        $new->name = $request->input('name');
        $new->birth_date = $request->input('birth_date');
        $new->address = $request->input('address');
        $new->date_of_employment = $request->input('date_of_employment');
        $new->position =$request->input('position');
        $new->contact_no = $request->input('contact_no');
        $new->daily_rate = $request->input('daily_rate');
        if($request->isMethod('put')) $new->is_active = $request->input('is_active');
    

        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function print($employee_id,$half,$month,$year){
        $employee = Employee::findOrFail($employee_id);
        $wage = Wage::where('half',$half)
        ->where('month',$month)
        ->where('year',$year)
        ->first();

        return view('print_pay_slip')
        ->with('employee',$employee)
        ->with('wage',$wage);

    }
}
