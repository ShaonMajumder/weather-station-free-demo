<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorDataController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sensor-dashboard', function () {
    return view('sensor-dashboard');
});


Route::get('/sensor-data', [SensorDataController::class, 'showSensorDataView'])->name('sensor.data.view');