<?php

use App\Http\Controllers\Candidate\Jobs\ApplicationController as JobApplicationController;
use App\Http\Controllers\Candidate\Jobs\VacancyController as JobVacancyController;

Route::prefix('jobs')->name('jobs.')->group(function () {

    // -------------------------------------------------------
    // VACANCIES — publik, tidak perlu login
    // GET /candidate/jobs/vacancies         → index (daftar lowongan)
    // GET /candidate/jobs/vacancies/{uuid}  → show  (detail lowongan)
    // -------------------------------------------------------
    Route::resource('vacancies', JobVacancyController::class)
        ->only(['index', 'show'])
        ->parameters(['vacancies' => 'uuid']);

    // -------------------------------------------------------
    // APPLICATIONS — wajib login kandidat
    // GET  /candidate/jobs/applications           → index (daftar lamaran saya)
    // GET  /candidate/jobs/applications/{uuid}    → show  (form lamaran)
    // POST /candidate/jobs/applications/{uuid}    → store (submit lamaran)
    // GET  /candidate/jobs/applications/{uuid}/success → halaman sukses
    // -------------------------------------------------------
    Route::middleware(['auth:candidate', 'verified'])->group(function () {
        Route::resource('applications', JobApplicationController::class)
            ->only(['index', 'show', 'store'])
            ->parameters(['applications' => 'uuid']);

        Route::get('applications/{uuid}/success', [JobApplicationController::class, 'applySuccess'])
            ->name('applications.success');
    });
});