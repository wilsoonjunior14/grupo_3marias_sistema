<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\PDFController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::get('/clientData/{id}', [PDFController::class, 'getClientDataPDF']);
Route::get('/proposal/{id}', [PDFController::class, 'getProposalPDF']);
Route::get('/contract/{id}', [PDFController::class, 'getContractPDF']);