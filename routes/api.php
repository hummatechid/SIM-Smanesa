<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\{StudentController,
    TreatmentController,
    ViolationTypeController,
    TeacherController,
    PenggunaController
};
use App\Http\Controllers\Transaction\PermitController;
use App\Http\Controllers\Transaction\ViolationController;
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


// API MOBILE

// autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('custom.sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // list permit
    Route::prefix('permit')->group(function (){
        Route::post('/', [PermitController::class, 'updateStatus']);
        Route::get('/list-today', [PermitController::class, 'listToday']);
        Route::get('/{id}', [PermitController::class, 'detailList']);
    });
});
