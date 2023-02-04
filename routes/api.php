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
      
        Route::get('/units/{customer_id}',[UnitController::class,'show']);
        Route::post('/unit',[UnitController::class,'store']);
        Route::put('/unit',[UnitController::class,'store']);

        Route::get('/problems/{unit_id}',[ProblemController::class,'show']);
        Route::post('/problem',[ProblemController::class,'store']);
        Route::put('/problem',[ProblemController::class,'store']);
        Route::delete('/problem/{problem_id}',[ProblemController::class,'destroy']);

        Route::post('/service',[ServiceController::class,'store']);
        Route::get('/services',[ServiceController::class,'index']);
        Route::post('/add_service_payment',[ServiceController::class,'add_service_payment']);

        Route::post('/supplier',[SupplierController::class,'store']);
        
        Route::get('/payments/{customer_id}',[PaymentController::class,'show']);
        Route::post('/payment',[PaymentController::class,'store']);

        Route::get('/items',[ItemController::class,'index']);

        Route::get('/invoices/{customer_id}',[InvoiceController::class,'show']);
        Route::post('/invoice',[InvoiceController::class,'store']);

});
// AUTHENTICATION
Route::post('/auth/signin',[AuthController::class,'signin']);
