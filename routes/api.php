<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\CovenantsController;
use App\Http\Controllers\SimulationController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('institutions', InstitutionsController::class)->only([
    'index'
]);

Route::resource('covenants', CovenantsController::class)->only([
    'index'
]);

Route::resource('simulation', SimulationController::class)->only([
    'store'
]);