<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\{
    StudentController,
    TreatmentController,
    ViolationTypeController,
    TeacherController,
    PenggunaController
};
use App\Http\Controllers\Transaction\AttendanceController;
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
    Route::get('/token-access', [AuthController::class, 'getToken']);

    // list permit
    Route::prefix('permit')->group(function () {
        Route::post('/', [PermitController::class, 'updateStatus']);
        Route::get('/list-today', [PermitController::class, 'listToday']);
        Route::get('/list-student/{student_id}', [PermitController::class, 'studentList']);
        Route::get('/{id}', [PermitController::class, 'detailList']);
    });

    // student api
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::patch('/{id}/update-password', [TeacherController::class, 'updatePassword'])->name('updatePassword');
    });

    // pengguna api
    Route::prefix('user')->name('user.')->group(function () {
        Route::patch('{id}/update-password', [PenggunaController::class, 'updatePassword'])->name('updatePassword');
    });

    // student api
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/many-data', [StudentController::class, 'detailManyStudent'])->name('detail.many');
        Route::get('/one-data/{id}', [StudentController::class, 'detailOneStudent'])->name('detail.one');
    });

    // violation api
    Route::prefix('violation')->name('violation.')->group(function () {
        Route::get('/', [ViolationController::class, 'listViolation'])->name('list');
        Route::get('/stats', [ViolationController::class, 'listViolationStatistik'])->name('stats');
        Route::get('/count-must-student', [ViolationController::class, 'listMustStudent'])->name('count-student');
    });

    // attendence api
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'listAttendences'])->name('list');
        Route::post('/', [AttendanceController::class, 'store'])->name('api.store');
        Route::get('/new-attendance', [AttendanceController::class, 'newAttendences'])->name('api.new-list');
        Route::get('/late-attendance', [AttendanceController::class, 'studentLateAttendances'])->name('late-list');
        Route::get('/count-must-late', [AttendanceController::class, 'studentMustLate'])->name('api.must-late');
    });
});
