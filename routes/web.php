<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [SurveyController::class, 'getSurveyList']);
Route::get('survey/{code}', [SurveyController::class, 'getAggregatedData']);
// Route::get('/', function () {
//     return view('welcome');
// });
