<?php

use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/apply')->name('home');

Route::get('apply', [ApplicationController::class, 'create'])->name('apply');
Route::post('apply', [ApplicationController::class, 'store'])->name('apply.store');
Route::get('apply/thanks', [ApplicationController::class, 'thanks'])->name('apply.thanks');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/applicants')->name('dashboard');

    Route::get('applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('applicants/{applicant}', [ApplicantController::class, 'show'])->name('applicants.show');
    Route::patch('applicants/{applicant}', [ApplicantController::class, 'update'])->name('applicants.update');
    Route::delete('applicants/{applicant}', [ApplicantController::class, 'destroy'])->name('applicants.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
