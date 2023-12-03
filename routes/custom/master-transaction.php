<?php

use App\Http\Controllers\Transaction\{AttendanceController, PermitController, ViolationController};
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
    
    Route::resource('attendance', AttendanceController::class);
    
    // get controller from PermitController
    Route::prefix('permit')->group(function(){
        Route::get('/waiting-acception', [PermitController::class, 'showAccListPage']);
        Route::get('/get-main-data', [PermitController::class, 'getDatatablesData'])->name('permit.get-main-datatables');
        Route::post('/update/many-data', [PermitController::class, 'updateManyData'])->name('permit.updateManyData');
        Route::resource('/', PermitController::class);
        Route::post('/soft-delete/{id}', [PermitController::class, 'softDestroy'])->name('permit.softDelete');
    });

    Route::get('violation/get-main-data', [ViolationController::class, 'getDatatablesData'])->name('violation.get-main-datatables');
    Route::resource('violation', ViolationController::class);
    
});
