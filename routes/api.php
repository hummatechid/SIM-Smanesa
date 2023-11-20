<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\{StudentController,
    TreatmentController,
    ViolationTypeController,
    TeacherController,
    PenggunaController
};
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

Route::get('violation-type/get-main-data', [ViolationTypeController::class, 'getDatatablesData']);
Route::get('treatment/get-main-data', [TreatmentController::class, 'getDatatablesData']);
Route::get('teacher/get-main-data', [TeacherController::class, 'getDatatablesData']);
Route::get('user/get-main-data', [PenggunaController::class, 'getDatatablesData']);
Route::get('student/get-main-data', [StudentController::class, 'getDatatablesData']);
Route::get('violation/get-main-data', [ViolationController::class, 'getDatatablesData']);
