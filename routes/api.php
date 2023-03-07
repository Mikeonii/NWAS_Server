<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SummaryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/user',[AuthController::class,'store']);
Route::group(['middleware'=>['auth:sanctum']],function(){
        Route::get('/auth/attempt',[AuthController::class,'attempt']);
        Route::post('/auth/signout',[AuthController::class,'signout']);
        Route::post('/customer',[CustomerController::class,'store']);
        Route::put('/customer',[CustomerController::class,'store']);
        Route::get('/customers',[CustomerController::class,'index']);
        Route::delete('/customer/{customer_id}',[CustomerController::class,'destroy']);
      
        Route::get('/units/{customer_id}',[UnitController::class,'show']);
        Route::post('/unit',[UnitController::class,'store']);
        Route::put('/unit',[UnitController::class,'store']);
        Route::delete('/unit/{id}',[UnitController::class,'destroy']);
 
        Route::get('/problems/{unit_id}',[ProblemController::class,'show']);
        Route::post('/problem',[ProblemController::class,'store']);
        Route::put('/problem',[ProblemController::class,'store']);
        Route::delete('/problem/{problem_id}',[ProblemController::class,'destroy']);

        Route::post('/service',[ServiceController::class,'store']);
        Route::get('/services',[ServiceController::class,'index']);
        Route::post('/add_service_payment',[ServiceController::class,'add_service_payment']);

        Route::post('/supplier',[SupplierController::class,'store']);
        Route::get('/suppliers',[SupplierController::class,'index']);
        
        Route::get('/payments/{customer_id}',[PaymentController::class,'show']);
        Route::post('/payment',[PaymentController::class,'store']);
        Route::delete('/payment/{payment_id}/{invoice_id}',[PaymentController::class,'destroy']);

        Route::get('/items',[ItemController::class,'index']);
        Route::post('/item',[ItemController::class,'store']);
        Route::put('/item',[ItemController::class,'store']);

        Route::get('/invoices/{customer_id}',[InvoiceController::class,'show']);
        Route::post('/invoice',[InvoiceController::class,'store']);
        Route::delete('/invoice/{invoice_id}',[InvoiceController::class,'delete_invoice']);
        Route::get('/unpaid_invoices',[InvoiceController::class,'get_unpaid_invoices']);
        Route::post('/add_payables_to_invoice',[InvoiceController::class,'add_payables_to_invoice']);
        Route::delete('/delete_payable_from_invoice/{payable_id}/{is_quote}/{invoice_id}',[InvoiceController::class,'delete_payable_from_invoice']);

        Route::get('/expenses',[ExpenseController::class,'index']);
        Route::post('/expense',[ExpenseController::class,'store']);
        Route::put('/expense',[ExpenseController::class,'store']);
        Route::delete('/expense/{expense_id}',[ExpenseController::class,'destroy']);
        Route::get('/update_invoice_balance',[InvoiceController::class,'update_invoice_balance']);

        Route::get('/summary',[SummaryController::class,'get_summary']);
        Route::get('/yearly_summary',[SummaryController::class,'get_yearly_summary']);
        Route::get('/get_picked_up_units',[SummaryController::class,'get_picked_up_units']);

});
// AUTHENTICATION
Route::post('/auth/signin',[AuthController::class,'signin']);
