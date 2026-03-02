<?php

use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\JobsController as AdminJobsController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ScheduleInterviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
        Route::resource('jobs', AdminJobsController::class)->except(['show']);
        Route::get('jobs/datatables', [AdminJobsController::class, 'datatables'])->name('jobs.datatables');

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


Route::prefix('client')->name('client.')->group(function () {
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
        Route::get('/', [JobsController::class, 'index'])->name('index');
        Route::get('{uuid}', [JobsController::class, 'detail'])->name('detail');

        Route::group(['middleware' => ['auth:candidate', 'verified']], function () {
            Route::get('{uuid}/apply', [JobsController::class, 'apply'])->name('apply');
            Route::post('{uuid}/apply/process', [JobsController::class, 'applyProcess'])->name('apply-process');
            Route::get('{uuid}/apply/success', [JobsController::class, 'applySuccess'])->name('apply-success');
        });
    });

    // Candidate authenticated routes
    Route::group(['middleware' => ['auth:candidate', 'verified']], function () {
        Route::prefix('my')->name('my.')->group(function () {
            Route::get('interview', [ApplyController::class, 'myInterviews'])->name('interview');
            Route::get('apply', [ApplyController::class, 'myApplies'])->name('apply');
            Route::get('cv', [ApplyController::class, 'myCV'])->name('cv');
            Route::get('cv/create', [ApplyController::class, 'createMyCV'])->name('cv.create');
            Route::post('cv/process', [ApplyController::class, 'storeMyCV'])->name('cv.process');
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
