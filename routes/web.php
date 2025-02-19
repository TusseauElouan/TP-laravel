<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\LangueController;
use App\Http\Controllers\MotifController;
use App\Http\Controllers\JourFerieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColorPreferenceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\TimeAccessController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['set.language', 'auth'])->group(function () {

    Route::get('/time-restriction', function () {
        return view('time-restriction');
    })->name('time-restriction');

    Route::middleware('time.restrict')->group(function () {
    Route::get('/', [AccueilController::class, 'index'])->name('accueil');

    Route::get('/welcome', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return redirect('/');
    })->middleware(['auth'])->name('dashboard');

    Route::prefix('time-access')->name('time-access.')->group(function () {
        Route::get('/', [TimeAccessController::class, 'index'])->name('index');
        Route::put('/{user}', [TimeAccessController::class, 'update'])->name('update');
        Route::post('/{user}/activate', [TimeAccessController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [TimeAccessController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/reset', [TimeAccessController::class, 'reset'])->name('reset');
    });

    Route::get('motif/info', [MotifController::class, 'info'])->name('motif.info');

    Route::resource('absence', AbsenceController::class);
    Route::post('absence/{absence}/validate', [AbsenceController::class, 'validate'])->name('absence.validate');
    Route::post('absence/{absence}/refuse', [AbsenceController::class, 'refuse'])->name('absence.refuse');
    Route::post('absence/{absence}/restore', [AbsenceController::class, 'restore'])->name('absence.restore');
    Route::get('/absence/{absence}/confirm', [AbsenceController::class, 'showValidationPage'])->name('absence.confirmValidation');
    Route::get('/absence/{absence}/justificatif', [AbsenceController::class, 'downloadJustificatif'])
    ->name('absence.justificatif.download');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('langue/change', [LangueController::class, 'change'])->name('langue.change');

    Route::resource('user', UserController::class);
    Route::resource('motif', MotifController::class);
    Route::resource('role', RoleController::class);

    // Routes du calendrier
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/api/absences/{userId?}', [CalendarController::class, 'getAbsences'])
        ->where('userId', 'all|[0-9]+')
        ->name('api.absences');

    Route::get('/preferences/colors', [ColorPreferenceController::class, 'index'])->name('preferences.colors');
    Route::post('/preferences/colors', [ColorPreferenceController::class, 'store'])->name('preferences.colors.store');

    Route::get('/absences/export', [ExportController::class, 'exportAbsences'])->name('absences.export');


    // Routes des jours fériés
    Route::get('/joursferies', [JourFerieController::class, 'index'])->name('joursferies.index');
    Route::prefix('api')->group(function () {
        Route::get('/joursferies', [JourFerieController::class, 'getAll']);
        Route::post('/joursferies', [JourFerieController::class, 'store']);
        Route::get('/joursferies/{jourFerie}', [JourFerieController::class, 'show']);
        Route::put('/joursferies/{jourFerie}', [JourFerieController::class, 'update']);
        Route::delete('/joursferies/{jourFerie}', [JourFerieController::class, 'destroy']);
        });
    });
});

require __DIR__.'/auth.php';
