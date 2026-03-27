<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AdminController;


Route::middleware('guest')->group(function() {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('password/forgot', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('password/forgot', [AuthController::class, 'sendResetCode'])->name('password.email');
    Route::get('password/verify', [AuthController::class, 'showVerifyForm'])->name('password.verify.form');
    Route::post('password/verify', [AuthController::class, 'verifyCode'])->name('password.verify');
    Route::get('password/reset', [AuthController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// User
Route::middleware('auth')->group(function() {
    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard');
Route::get('donate', [DonationController::class,'create'])->name('donate.form');
Route::post('donate', [DonationController::class,'store'])->name('donate.store');
Route::get('donate/payment/{id}', [DonationController::class,'payment'])->name('donate.payment');
Route::post('donate/create-invoice/{id}', [DonationController::class,'createInvoice'])
     ->name('donate.create-invoice');
Route::get('donations', [DonationController::class,'index'])->name('donations.index');
Route::post('xendit/webhook', [DonationController::class,'webhook'])->name('xendit.webhook');

 Route::get('submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('submissions/{id}', [SubmissionController::class, 'show'])->name('submissions.show');
    
   Route::get('submissions/{id}/disbursement', [SubmissionController::class, 'createDisbursement'])
         ->name('submissions.disbursement.create');
    Route::post('submissions/{id}/disbursement', [SubmissionController::class, 'storeDisbursement'])
         ->name('submissions.disbursement.store');
    Route::get('report', [DashboardController::class,'report'])->name('report');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


    // Admin-only
Route::middleware(['auth', 'can:admin'])
     ->prefix('admin')
     ->group(function() {
        Route::get('donations', [AdminController::class,'donations'])->name('admin.donations');
          Route::get('donations', [AdminController::class, 'donations'])->name('admin.donations');
        Route::post('donations/{id}/approve', [AdminController::class, 'approveDonation'])->name('admin.donations.approve');
        Route::post('donations/{id}/reject', [AdminController::class, 'rejectDonation'])->name('admin.donations.reject');
        
        Route::get('submissions', [AdminController::class,'submissions'])->name('admin.submissions');
        Route::post('submissions/{id}/approve', [AdminController::class,'approveSubmission'])->name('admin.submissions.approve');
        Route::post('submissions/{id}/reject', [AdminController::class,'rejectSubmission'])->name('admin.submissions.reject');
        
        Route::get('disbursements', [AdminController::class, 'disbursements'])->name('admin.disbursements'); // Perbaiki name
        Route::post('disbursements/{id}/process', [AdminController::class, 'processDisbursement'])->name('admin.disbursements.process');
        Route::post('disbursements/{id}/complete', [AdminController::class, 'completeDisbursement'])->name('admin.disbursements.complete');
        Route::post('disbursements/{id}/fail', [AdminController::class, 'failDisbursement'])->name('admin.disbursements.fail');

        // Routes baru untuk Manajemen User
        Route::get('users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        // Routes baru untuk Pengelolaan Dana
        Route::get('finance', [AdminController::class, 'finance'])->name('admin.finance');
 
    });
    
    Route::get('/', function () {
        return redirect()->route('login.form'); // atau ke 'dashboard', bebas
    });
    
});