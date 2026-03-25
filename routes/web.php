<?php

use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ScheduleInterviewController;
use App\Http\Controllers\Candidate\AuthController;
use App\Http\Controllers\Candidate\Jobs\VacancyController as JobVacancyController;
use App\Http\Controllers\Candidate\Jobs\ApplicationController as JobApplicationController;
use App\Http\Controllers\Candidate\DocumentController;
use App\Http\Controllers\Candidate\HomeController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/candidate');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');

        // Batches
        Route::resource('batches', BatchController::class)->except(['show']);
        Route::prefix('batches')->name('batches.')->group(function () {
            Route::get('datatables', [BatchController::class, 'datatables'])->name('datatables');
            Route::get('{id}/status', [BatchController::class, 'status'])->name('status');
        });

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('datatables', [CategoryController::class, 'datatables'])->name('datatables');
            Route::get('{id}/status', [CategoryController::class, 'status'])->name('status');
        });

        // Jobs
        Route::resource('jobs', JobManagementController::class)->except(['show']);
        Route::get('jobs/datatables', [JobManagementController::class, 'datatables'])->name('jobs.datatables');

        // Candidates
        Route::resource('candidates', CandidateController::class)->except(['create','store','show','edit','update','destroy']);
        Route::get('candidates/datatables', [CandidateController::class, 'datatables'])->name('candidates.datatables');

        // Applies
        Route::resource('applies', ApplicantController::class)->only(['index','show','update']);
        Route::get('applies.datatables', [ApplicantController::class, 'datatables'])->name('applies.datatables');

        // Schedule Interviews
        Route::resource('schedule-interviews', ScheduleInterviewController::class)->except(['show']);
        Route::prefix('schedule-interviews')->name('schedule-interviews.')->group(function () {
            Route::get('datatables', [ScheduleInterviewController::class, 'datatables'])->name('datatables');
            Route::get('{id}/invitation', [ScheduleInterviewController::class, 'invitation'])->name('invitation');
        });
    });

    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login/process', [LoginController::class, 'process'])->name('process');
});


Route::prefix('candidate')->name('candidate.')->group(function () {
    // Auth routes
    Route::prefix('login')->name('login.')->group(function () {
        Route::get('/', [AuthController::class, 'login'])->name('form');
        Route::post('process', [AuthController::class, 'process'])->name('process');
    });

    Route::prefix('register')->name('register.')->group(function () {
        Route::get('/', [AuthController::class, 'register'])->name('form');
        Route::post('verify', [AuthController::class, 'verify'])->name('verify');
    });

    Route::get('email/verification/{token}', [AuthController::class, 'verification'])->name('email-verification');

    // Home
    Route::get('/', [HomeController::class, 'home'])->name('home');

    // Jobs
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::resource('vacancies', JobVacancyController::class)->only(['index', 'show'])->parameters(['vacancies' => 'uuid']);
        Route::get('vacancies/{uuid}/apply', [JobVacancyController::class, 'apply'])->name('vacancies.apply')->middleware(['auth:candidate', 'verified']);        
        Route::resource('applications', JobApplicationController::class)->only(['show', 'store'])->parameters(['applications' => 'uuid']);
        Route::get('applications/{uuid}/success', [JobApplicationController::class, 'applySuccess'])->name('applications.success');
    });

    // My
    Route::group(['middleware' => ['auth:candidate', 'verified']], function () {
        Route::prefix('my')->name('my.')->group(function () {
            // Route::get('interview', [ApplyController::class, 'myInterviews'])->name('interview');
            // Route::get('apply', [ApplyController::class, 'myApplies'])->name('applies');
            // Route::get('cv', [ApplyController::class, 'myCV'])->name('cv');
            // Route::get('cv/create', [ApplyController::class, 'createMyCV'])->name('cv.create');
            // Route::post('cv/process', [ApplyController::class, 'storeMyCV'])->name('cv.process');

            Route::middleware(['auth:candidate', 'verified'])->group(function () {
                Route::resource('applications', JobApplicationController::class)->only(['index'])->parameters(['applications' => 'uuid']);
            });
            Route::resource('documents', DocumentController::class);
        });

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
});


Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel.', function ($message) {
            $message->to('zab.com2004@gmail.com')
                    ->subject('Test Email from Laravel MADTIVE');
        });

        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

Route::get('/test-hash', function() {
    $plain = '123123123';
    $hashFromDB = App\Models\Candidate::where('email', 'usertest@gmail.com')->first()->password;
    
    // Generate ulang hash untuk test
    $newHash = Hash::make($plain);
    
    return [
        'hash_lama_db' => $hashFromDB,
        'hash_baru' => $hashFromDB,
        'cocok_dengan_hash_lama' => Hash::check($plain, $hashFromDB) ? 'YA' : 'TIDAK',
        'cocok_dengan_hash_baru' => Hash::check($plain, $newHash) ? 'YA' : 'TIDAK',
    ];
});
