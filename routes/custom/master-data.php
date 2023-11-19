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
    Route::resource('violation-type', ViolationTypeController::class);
    Route::post('violation-type/soft-delete/{id}', [ViolationTypeController::class, 'softDestroy'])->name('violation-type.softDelete');

    // get controller from TreatmentController
    Route::resource('treatment', TreatmentController::class);
    Route::post('treatment/soft-delete/{id}', [TreatmentController::class, 'softDestroy'])->name('treatment.softDelete');

    // get controller from PenggunaController
    Route::resource('pengguna', PenggunaController::class);
    Route::post('pengguna/soft-delete/{id}', [PenggunaController::class, 'softDestroy'])->name('pengguna.softDelete');

    Route::prefix('students')->name('student.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('sync-students', [StudentController::class, 'syncStudents'])->name('sync');
    });

    Route::resource('teacher', TeacherController::class);

    Route::resource('user', PenggunaController::class);
});
