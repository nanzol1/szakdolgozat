<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyProfileController;
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
});

Route::middleware('auth:company')->group(function () {
    Route::get('/company-profile', [CompanyProfileController::class, 'editCompany'])->name('cprofile.edit');
    Route::post('/company-profile', [CompanyProfileController::class, 'update'])->name('cprofile.update');
    Route::delete('/company-profile', [CompanyProfileController::class, 'destroy'])->name('cprofile.destroy');
    Route::post('/profile/createjob', [CompanyProfileController::class, 'createJobs'])->name('cprofile.create.job');
});

Route::get('/munkak',[JobsController::class,'index'])->name('');



require __DIR__.'/auth.php';
