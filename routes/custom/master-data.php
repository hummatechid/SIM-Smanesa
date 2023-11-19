<?php

use App\Http\Controllers\Master\{
    TreatmentController,
    ViolationTypeController,
    TeacherController,
    PenggunaController
};
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {
    // get controller from ViolationTypeController 
    Route::resource('violation-type', ViolationTypeController::class);
    Route::post('violation-type/soft-delete/{id}', [ViolationTypeController::class, "softDestroy"]);
    
    // get controller from TreatmentController 
    Route::resource('treatment', TreatmentController::class);
    Route::post('treatment/soft-delete/{id}', [TreatmentController::class, "softDestroy"]);
    
    Route::resource('teacher', TeacherController::class);

    Route::resource('user', PenggunaController::class);
});