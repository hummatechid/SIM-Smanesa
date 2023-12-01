<?php

use App\Http\Controllers\Master\{StudentController,
    TreatmentController,
    ViolationTypeController,
    TeacherController,
    PenggunaController};
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
    Route::get('violation-type/get-main-data', [ViolationTypeController::class, 'getDatatablesData'])->name('violation-type.get-main-datatables');
    Route::resource('violation-type', ViolationTypeController::class);
    Route::post('violation-type/soft-delete/{id}', [ViolationTypeController::class, 'softDestroy'])->name('violation-type.softDelete');

    // get controller from TreatmentController
    Route::get('treatment/get-main-data', [TreatmentController::class, 'getDatatablesData'])->name('treatment.get-main-datatables');
    Route::resource('treatment', TreatmentController::class);
    Route::post('treatment/soft-delete/{id}', [TreatmentController::class, 'softDestroy'])->name('treatment.softDelete');

    // get controller from PenggunaController
    Route::resource('pengguna', PenggunaController::class);
    Route::post('pengguna/soft-delete/{id}', [PenggunaController::class, 'softDestroy'])->name('pengguna.softDelete');
    
    Route::get('teacher/get-main-data', [TeacherController::class, 'getDatatablesData'])->name('teacher.get-main-datatables');
    Route::get('teacher/{id}/edit-password', [TeacherController::class, 'editPassword'])->name('teacher.editPassword');
    Route::patch('teacher/{id}/update-password', [TeacherController::class, 'updatePassword'])->name('teacher.updatePassword');
    Route::resource('teacher', TeacherController::class);
    Route::post('teacher/soft-delete/{id}', [TeacherController::class, 'softDestroy'])->name('teacher.softDestroy');
    
    Route::get('user/get-main-data', [PenggunaController::class, 'getDatatablesData'])->name('user.get-main-datatables');
    Route::get('user/{id}/edit-password', [PenggunaController::class, 'editPassword'])->name('user.editPassword');
    Route::patch('user/{id}/update-password', [PenggunaController::class, 'updatePassword'])->name('user.updatePassword');
    Route::resource('user', PenggunaController::class);
    Route::post('user/soft-delete/{id}', [PenggunaController::class, 'softDestroy'])->name('user.softDelete');

    Route::prefix('students')->name('student.')->group(function () {
        Route::get('get-main-data', [StudentController::class, 'getDatatablesData'])->name('get-main-datatables');
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('sync-students', [StudentController::class, 'syncStudents'])->name('sync');
    });
});
