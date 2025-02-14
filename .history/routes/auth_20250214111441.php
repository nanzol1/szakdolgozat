<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::get('register/{type}', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register/{type}', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('destroycv',[ProfileController::class,'destroycv'])->name('profile.destroycv');
    Route::post('updateInterests',[ProfileController::class,'updateInterests'])->name('profile.update.interests');
    Route::post('updateEducation',[ProfileController::class,'updateEducation'])->name('profile.update.education'); 
    Route::get('profile/institutions',[ProfileController::class,'getInstitutions'])->name('profile.institutions.search'); 
    Route::post('updateintroduction',[ProfileController::class,'updateIntroduction'])->name('profile.update.introduction'); 

});

Route::middleware('auth:company')->group(function () {
    /*Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');*/
    Route::get('profile/employment',[CompanyProfileController::class,'getEmploymentsType'])->name('profile.employment.search'); 
    Route::get('profile/schedules',[CompanyProfileController::class,'getWorkSchedules'])->name('profile.schedules.search'); 


    Route::post('clogout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('clogout');
});
