<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\LangueController;
use App\Http\Controllers\MotifController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['set.language', 'auth'])->group(function () {
    Route::get('/', [AccueilController::class, 'index'])->name('accueil');

    Route::get('/welcome', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return redirect('/');
    })->middleware(['auth'])->name('dashboard');

    Route::resource('absence', AbsenceController::class);
    Route::post('absence/{absence}/validate', [AbsenceController::class, 'validate'])->name('absence.validate');
    Route::post('absence/{absence}/restore', [AbsenceController::class, 'restore'])->name('absence.restore');
    Route::get('/absence/{absence}/confirm', [AbsenceController::class, 'showValidationPage'])->name('absence.confirmValidation');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('langue/change', [LangueController::class, 'change'])->name('langue.change');

    Route::resource('user', UserController::class);
    Route::resource('motif', MotifController::class);
});

require __DIR__.'/auth.php';
