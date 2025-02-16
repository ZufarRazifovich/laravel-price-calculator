<?php

use App\Http\Controllers\PriceCalculatorController;
use App\Http\Controllers\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/calculate', [PriceCalculatorController::class, 'calculate']);
Route::get('/products', [PriceCalculatorController::class, 'index']);
Route::get('/countries', [CountryController::class, 'index']);