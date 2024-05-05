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

Route::get('/home', function () { return view('welcome'); });
Route::get('/', function () { return view('welcome'); });
Route::any('/admin/{page}', function() { return view('welcome'); });
Route::any('/admin/{page}/{id}', function() { return view('welcome'); });
Route::any('/contracts', function() { return view('welcome'); });
Route::any('/contracts/{page}', function() { return view('welcome'); });
Route::any('/contracts/{page}/{id}', function() { return view('welcome'); });
Route::any('/stocks', function() { return view('welcome'); });
Route::any('/stocks/{page}', function() { return view('welcome'); });
Route::any('/stocks/{page}/{id}', function() { return view('welcome'); });
Route::any('/money', function() { return view('welcome'); });
Route::any('/money/{page}', function() { return view('welcome'); });
Route::any('/money/{page}/{id}', function() { return view('welcome'); });
Route::any('/login', function() { return view('welcome'); });
Route::any('/recovery', function() { return view('welcome'); });
Route::any('/account', function() { return view('welcome'); });

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::get('/clientData/{id}', [PDFController::class, 'getClientDataPDF']);
Route::get('/proposal/{id}', [PDFController::class, 'getProposalPDF']);
Route::get('/contract/{id}', [PDFController::class, 'getContractPDF']);
Route::get('/alvara/{id}', [PDFController::class, 'getAlvaraPDF']);

Route::get('/v', function() {
    function getStringValue($value, $amount) {
        switch ($amount) {
            case 1:
                return getUnityValue($value);
            case 2:
                return getDecimalValue($value);
            case 3:
                return getMilValue($value);
            default:
                break;
        }
    }

    function getUnityValue($index) {
        $u = array("1" => "um", "2" => "dois", "3" => "três", "4" => "quatro", "5" => "cinco", "6" => "seis", "7" => "sete", "8" => "oito", "9", "nove");
        return $u[$index];
    }

    function getDecimalValue($index) {
        $d = array("1" => "dez", "2" => "vinte", "3" => "trinta", "4" => "quarenta", "5" => "cinquenta", "6" => "sessenta", "7" => "setenta", "8" => "oitenta", "9", "noventa");
        return $d[$index];
    }

    function getMilValue($index) {
        $c = array("1" => "cento", "2" => "duzentos", "3" => "trezentos", "4" => "quatrocentos", "5" => "quinhentos", "6" => "seiscentos", "7" => "setecentos", "8" => "oitocentos", "9", "novecentos");
        return $c[$index];
    }

    echo "testando imprimir valores por extenso" . "<br></br>";
    $value = "175000.23";
    echo $value . "<br></br>";

    $getCents = explode(".", $value)[1];
    if (!is_null($getCents)) {
        $centsString = [];
        $amountCents = strlen($getCents);
        for ($i = 1; $i <= strlen($getCents); $i++) {
            $centsValue = substr($getCents, $i - 1, $i);
            $stringValue = getStringValue($centsValue, $amountCents);
            $centsString[] = $stringValue;
            $amountCents -= 1;
        }
        $centsString = join(" e ", $centsString) . " centavos";
        echo $centsString;
    }

    $getValue = explode(".", $value)[0];
    $valueString = [];
    $amountValue = strlen($getValue);
    
    

});