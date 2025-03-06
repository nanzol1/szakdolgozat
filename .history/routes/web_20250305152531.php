<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\CountyListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\JobsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [BaseController::class,'index'])->name('/');

Route::get('/dashboard', [DashboardController::class,'index'])
->middleware(['auth', 'verified'])->name('dashboard');
Route::get('applied',[DashboardController::class,'getAppliedJobsTable'])
->middleware(['auth', 'verified'])->name('applied.jobs.load');


Route::get('/cdashboard',[DashboardController::class,'companyIndex'])
->middleware(['auth:company', 'verified'])->name('cdashboard');

Route::get('/munkak',[JobsController::class,'index'])->name('jobs.show');
Route::get('cvmaker',[CVController::class,'index'])->name('cv.show');
Route::post('cvmaker/store',[CVController::class,'store'])->name('cv.store');
Route::get('/munka/{id}',[JobsController::class,'jobView'])->name('jobs.view');
Route::get('/munka',[BaseController::class,'index'])->name('jobs.index');
Route::get('/cvmaker/previewCV',[CVController::class,'showCVTemplate'])->name('jobs.cv.preview');
Route::post('/cv/pdf',[CVController::class,'generatePdf'])->name('cv.pdf.generate');
Route::get('/cvmaker/{datas}',[CountyListController::class,'getDataFromAddress'])->name('cv.getcodes');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/erdekeltsegeim', [ProfileController::class, 'editInterests'])->name('profile.edit.interests');
    Route::get('/profile/kepzettsegek', [ProfileController::class, 'editEducations'])->name('profile.edit.educations');
    Route::get('/profile/cv', [ProfileController::class, 'editCV'])->name('profile.edit.cv');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('munkaim',[JobsController::class,'getMyJobs'])->name('profile.myjobs');
    Route::post('profile/uploadCV',[ProfileController::class,'uploadProfileCV'])->name('profile.save.cvfile');
    Route::post('/munka/{id}/apply',[JobsController::class,'applyForJob'])->name('jobs.apply');
    Route::post('/munka/{id}/apply-revoke',[JobsController::class,'revokeApplication'])->name('jobs.apply.revoke');
    Route::post('profile/cv/createPin',[ProfileController::class,'createPinCode'])->name('profile.cv.pincode.create');
    Route::post('profile/cv/validPin',[ProfileController::class,'getValidPinCode'])->name('profile.cv.pincode.validate');
});

Route::middleware('auth:company')->group(function () {
    Route::get('/company-profile', [CompanyProfileController::class, 'editCompany'])->name('cprofile.edit');
    Route::post('/company-profile', [CompanyProfileController::class, 'update'])->name('cprofile.update');
    Route::post('/company-profile', [CompanyProfileController::class, 'destroy'])->name('cprofile.destroy');
    Route::post('/hirdetes/feladas', [CompanyProfileController::class, 'createJobs'])->name('cprofile.create.job');
    Route::get('/munkaim',[JobsController::class,'getListedJobs'])->name('cprofile.myjobs');
    Route::get('company-profile/munka/{id}',[JobsController::class,'editListedJob'])->name('cprofile.myjobs.edit');
    Route::get('/search-employment',[CompanyProfileController::class,'getEmploymentsType'])->name('cprofile.employment.search'); 
    Route::get('/search-schedules',[CompanyProfileController::class,'getWorkSchedules'])->name('cprofile.schedules.search'); 
    Route::post('company_profile/munkaim/edit/save',[JobsController::class,'saveListedJob'])->name('cprofile.save.job'); 
    Route::get('company-profile/munka/{id}/jelentkezo/{jelentkezoId}',[JobsController::class,'showApplicant'])->name('cprofile.job.applicant');
    Route::put('company-profile/munka/{id}/jelentkezo/{jelentkezoId}/updateStatus/{statusId}',[JobsController::class,'updateStatus'])->name('cprofile.job.updatestatus');
});

Route::get('admin/login',[AdminLoginController::class,'index'])->name('admin.login');


Route::middleware(['admin'])->group(function () {
    Route::get('admin/dashboard',[AdminLoginController::class,'dashboard'])->name('admin.dashboard');

    Route::get('admin/users',[UserController::class,'getUsers'])->name('admin.users.show');
    Route::get('admin/users/results',[UserController::class,'getUsers'])->name('admin.users.search'); 
    Route::get('admin/user/{id}',[UserController::class,'showUser'])->name('admin.user.show');
    Route::patch('admin/user/{id}/setStatus',[UserController::class,'setStatus'])->name('admin.user.setstatus');
    Route::post('{id}/randomPassword',[UserController::class,'generateRandomPassword'])->name('admin.user.randompassword');

    Route::get('admin/companies',[CompanyController::class,'getCompanies'])->name('admin.companies.show');
    Route::get('admin/companies/results',[CompanyController::class,'getCompanies'])->name('admin.companies.search'); 
    Route::get('admin/company/{id}',[CompanyController::class,'showCompany'])->name('admin.company.show');
    Route::post('{id}/randomPassword',[CompanyController::class,'generateRandomPasswordCompany'])->name('admin.company.randompassword');
    Route::patch('admin/company/{id}/setStatus',[CompanyController::class,'setStatus'])->name('admin.company.setstatus');

    
});




require __DIR__.'/auth.php';
