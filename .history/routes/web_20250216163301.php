<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\JobsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cdashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth:company', 'verified'])->name('cdashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('profile/munkaim',[JobsController::class,'getMyJobs'])->name('profile.myjobs');
    Route::get('profile/cvmaker',[CVController::class,'index'])->name('profile.cv.show');
});

Route::middleware('auth:company')->group(function () {
    Route::get('/company-profile', [CompanyProfileController::class, 'editCompany'])->name('cprofile.edit');
    Route::post('/company-profile', [CompanyProfileController::class, 'update'])->name('cprofile.update');
    Route::delete('/company-profile', [CompanyProfileController::class, 'destroy'])->name('cprofile.destroy');
    Route::post('/company-profile/createjob', [CompanyProfileController::class, 'createJobs'])->name('cprofile.create.job');
    Route::get('company-profile/munkaim',[JobsController::class,'getListedJobs'])->name('cprofile.myjobs');
    Route::get('company-profile/munka/{id}',[JobsController::class,'editListedJob'])->name('cprofile.myjobs.edit');
    Route::get('/search-employment',[CompanyProfileController::class,'getEmploymentsType'])->name('cprofile.employment.search'); 
    Route::get('/search-schedules',[CompanyProfileController::class,'getWorkSchedules'])->name('cprofile.schedules.search'); 
    Route::post('company_profile/munkaim/edit/save',[JobsController::class,'saveListedJob'])->name('cprofile.save.job'); 
    Route::get('company-profile/munka/{id}/jelentkezo/{jelentkezoId}',[JobsController::class,'showApplicant'])->name('cprofile.job.applicant');
    Route::patch('company-profile/munka/{id}/jelentkezo/{jelentkezoId}/updateStatus/{statusId}',[JobsController::class,'updateStatus'])->name('cprofile.job.updatestatus');
});

Route::get('/munkak',[JobsController::class,'index'])->name('jobs.show');
Route::get('/munka/{id}',[JobsController::class,'jobView'])->name('jobs.view');
Route::post('/munka/{id}/apply',[JobsController::class,'applyForJob'])->name('jobs.apply');
Route::post('/munka/{id}/apply-revoke',[JobsController::class,'revokeApplication'])->name('jobs.apply.revoke');




require __DIR__.'/auth.php';
