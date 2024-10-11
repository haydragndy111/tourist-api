<?php

use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\TouristAuthController;
use App\Http\Controllers\Api\Drivers\DriverController;
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
    'middleware' => ['api',],
], function () {
    Route::get('/', [TourController::class, 'index']);
    Route::get('/labels', [TourController::class, 'labels']);
    Route::get('/{tour}', [TourController::class, 'show']);
    Route::post('/create', [TourController::class, 'create']);
    Route::post('/{tour}/edit', [TourController::class, 'edit']);
    Route::post('/{tour}/open', [TourController::class, 'openTour']);
    Route::post('/{tour}/add-tourist', [TourController::class, 'addTourist']);
    Route::post('/{tour}/attach', [TourController::class, 'attachTour']);
});

Route::group([
    'prefix' => 'drivers',
    'middleware' => ['api',],
], function () {
    Route::get('/', [DriverController::class, 'index']);
    Route::get('/labels', [DriverController::class, 'labels']);
    Route::get('/{driver}', [DriverController::class, 'show']);
    Route::post('/create', [DriverController::class, 'create']);
    Route::post('/{driver}/edit', [DriverController::class, 'edit']);
    Route::post('/{driver}/report', [DriverController::class, 'report']);
});

Route::group([
    'prefix' => 'programs',
    'middleware' => ['api', 'user-access'],
], function () {
    Route::get('/', [ProgramController::class, 'index']);
    Route::get('/{program}', [ProgramController::class, 'show']);
    Route::post('/create', [ProgramController::class, 'create']);
    Route::post('/{program}/edit', [ProgramController::class, 'edit']);
});

Route::post('/register',[TouristAuthController::class, 'register'] );
Route::post('/login',[TouristAuthController::class, 'login'] );

Route::post('/admin/register',[AdminAuthController::class, 'register'] );
Route::post('/admin/login',[AdminAuthController::class, 'login'] );
Route::post('/admin/logout',[AdminAuthController::class, 'logout'] );
