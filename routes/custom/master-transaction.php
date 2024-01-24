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

// route scanner
Route::get('scan-attendance', [AttendanceController::class, 'scanAttendance'])->name('scan.index');
// Route::get('scan-attendance-camera', [AttendanceController::class, 'scanAttendanceCamera'])->name('scan-manual');
Route::get('attendance/get-main-data',[AttendanceController::class, 'getDatatablesData'])->name('attendance.get-main-datatables');
Route::post('attendance/present',[AttendanceController::class, 'store'])->name('presence.attendance');

Route::middleware(['auth'])->group(function () {
    Route::prefix('attendance')->name('attendance.')->controller(AttendanceController::class)->group(function() {
        Route::get('get-limited-data','getDatatablesLimit')->name('get-limit-datatables');
        Route::get('get-permit-data','getDatatablesPermit')->name('get-permit-datatables');
        Route::get('get-report-data','getReportDatatablesData')->name('get-report-datatables');
        Route::get('presence','presence')->name('presence');
        Route::get('report','report')->name('report');
        Route::post('presence','createPermit')->name('presence.create-permit');
        Route::post('sync-attendances','syncAttendanceToday')->name('sync');
        Route::get('/new-attendance','newAttendences')->name('new-list');
        Route::get('/count-must-late','studentMustLate')->name('must-late');
        Route::get('time-setting', 'timeSetting')->name('time-setting');
        Route::post('time-setting', '')->name('store-time-setting');
        Route::get('/time-setting/get-time', '')->name('get-time-setting');
    });
    Route::resource('attendance', AttendanceController::class);
    
    // get controller from PermitController
    Route::prefix('permit')->name('permit.')->group(function(){
        Route::get('/waiting-acception', [PermitController::class, 'showAccListPage']);
        Route::get('/get-main-data', [PermitController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::patch('/update/many-data', [PermitController::class, 'updateManyData'])->name('updateManyData');
        Route::post('/print', [PermitController::class, 'print'])->name('print');
        Route::post('/soft-delete/{id}', [PermitController::class, 'softDestroy'])->name('softDelete');
    });
    Route::resource('/permit', PermitController::class);

    Route::prefix('violation')->name('violation.')->group(function(){
        Route::get('/get-main-data', [ViolationController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::get('/get-student-data', [ViolationController::class, 'getDatatablesData'])->name('student');
        Route::get('report', [ViolationController::class, 'report'])->name('report');
        Route::get('/get-report-data', [ViolationController::class, 'getReportDatatablesData'])->name('get-report-datatables');
        Route::get('/count-must-student', [ViolationController::class, 'listMustStudent'])->name('count-student');
        Route::post('/soft-destroy/{id}', [ViolationController::class, 'softDestroy'])->name('soft-destroy');
    });
    Route::resource('/violation', ViolationController::class);
    
});
