<?php

use App\Http\Controllers\Api\Programs\ProgramController;
use App\Http\Controllers\Api\Tours\TourController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'tours',
], function () {
    Route::get('/', [TourController::class, 'index']);
    Route::get('/{tour}', [TourController::class, 'show']);
    Route::post('/create', [TourController::class, 'create']);
    Route::post('/{tour}/edit', [TourController::class, 'edit']);
    Route::post('/{tour}/open', [TourController::class, 'openTour']);
    Route::post('/{tour}/add-tourist', [TourController::class, 'addTourist']);
});

Route::group([
    'prefix' => 'programs',
], function () {
    Route::get('/', [ProgramController::class, 'index']);
    Route::get('/{program}', [ProgramController::class, 'show']);
    Route::post('/create', [ProgramController::class, 'create']);
    Route::post('/{program}/edit', [ProgramController::class, 'edit']);
});
