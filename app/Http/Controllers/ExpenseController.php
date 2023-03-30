<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Exception\Exception;
class ExpenseController extends Controller
{
    public function index(){
        return Expense::all();
    }
    public function destroy($expense_id){
        return Expense::where('id',$expense_id)->delete();
    }
    public static function store(Request $request){
        $new = $request->isMethod('put') ? Expense::findOrFail($request->id) : new Expense;
        $new->expense_description = $request->input('expense_description');
        $new->expense_amount = $request->input('expense_amount');
        $new->expense_type = $request->input('expense_type');
        $new->date_paid = $request->input('date_paid');
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
