<?php

use App\Http\Controllers\Api\V1\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('v1/vehicle_specifications',[VehicleController::class,'get_vehicle_specifications']);
Route::get('v1/vehicle_market_valuation',[VehicleController::class,'get_vehicle_market_valuation']);
Route::get('v1/vehicle_ownership_cost',[VehicleController::class,'get_vehicle_ownership_cost']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
