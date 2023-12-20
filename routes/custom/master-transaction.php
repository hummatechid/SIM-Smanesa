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
    
    Route::prefix('attendance')->name('attendance.')->group(function() {
        Route::get('get-main-data', [AttendanceController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::get('get-limited-data', [AttendanceController::class, 'getDatatablesLimit'])->name('get-limit-datatables');
        Route::get('get-permit-data', [AttendanceController::class, 'getDatatablesPermit'])->name('get-permit-datatables');
        Route::get('presence', [AttendanceController::class, 'presence'])->name('presence');
        Route::post('presence', [AttendanceController::class, 'createPermit'])->name('presence.create-permit');
        Route::get('/new-attendance', [AttendanceController::class, 'newAttendences'])->name('new-list');
        Route::get('/count-must-late', [AttendanceController::class, 'studentMustLate'])->name('must-late');
        Route::resource('/', AttendanceController::class);
    });
    
    // get controller from PermitController
    Route::prefix('permit')->name('permit.')->group(function(){
        Route::get('/waiting-acception', [PermitController::class, 'showAccListPage']);
        Route::get('/get-main-data', [PermitController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::post('/update/many-data', [PermitController::class, 'updateManyData'])->name('updateManyData');
        Route::resource('/', PermitController::class);
        Route::post('/soft-delete/{id}', [PermitController::class, 'softDestroy'])->name('softDelete');
    });

    Route::prefix('violation')->name('violation.')->group(function(){
        Route::get('/get-main-data', [ViolationController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::resource('/', ViolationController::class);
        Route::get('/count-must-student', [ViolationController::class, 'listMustStudent'])->name('count-student');
    });
    
});
