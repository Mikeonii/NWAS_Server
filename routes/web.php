<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UnitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/print/{type}/{invoice_id}',[InvoiceController::class,'print']);
Route::get('/print_job_order_slip/{data}/{unit_id}',[UnitController::class,'print_job_order_slip']);
Route::get('/', function () {
    return view('welcome');
});
