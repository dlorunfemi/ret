<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AssetController as ApiAssetController;
use App\Http\Controllers\Api\BalanceController as ApiBalanceController;
use App\Http\Controllers\Api\TransactionController as ApiTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user) {
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->route(($user->role ?? 'user') === 'admin' ? 'admin.dashboard' : 'assets');
    }

    return app(\App\Http\Controllers\AuthController::class)->showRegister();
})->name('signup');

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Email Verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = Auth::user();
    $wasVerified = $user?->hasVerifiedEmail() ?? false;
    $request->fulfill();

    if (! $wasVerified && $user) {
        Notification::send($user, new \App\Notifications\WelcomeEmail);
    }

    return redirect()->route('assets');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assets', [PageController::class, 'assets'])->name('assets');
    Route::get('/deposit', [PageController::class, 'deposit'])->name('deposit');
    Route::get('/withdrawal', [PageController::class, 'withdrawal'])->name('withdrawal');
    Route::get('/deposit-records', [PageController::class, 'depositRecords'])->name('deposit.records');
    Route::get('/withdrawal-records', [PageController::class, 'withdrawalRecords'])->name('withdrawal.records');
    Route::get('/transfer-history', [PageController::class, 'transferHistory'])->name('transfer.history');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::put('/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::put('/admin/withdrawals/{transaction}/approve', [AdminUserController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');

    // Admin resources
    Route::resource('/admin/assets', \App\Http\Controllers\Admin\AssetController::class)->names('admin.assets');
    Route::resource('/admin/asset-networks', \App\Http\Controllers\Admin\AssetNetworkController::class)->names('admin.asset_networks');
    Route::resource('/admin/transactions', \App\Http\Controllers\Admin\TransactionController::class)->names('admin.transactions');
    Route::resource('/admin/users', \App\Http\Controllers\Admin\UserManagementController::class)->names('admin.users')->except(['show']);
    // Balances moved under Users page as a modal-driven tool
    Route::get('/admin/users/{user}/balances', [\App\Http\Controllers\Admin\UserManagementController::class, 'balances'])->name('admin.users.balances');
    Route::put('/admin/users/{user}/balances', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateBalances'])->name('admin.users.balances.update');

    // Admin profile (change password)
    Route::get('/admin/profile/password', [\App\Http\Controllers\Admin\DashboardController::class, 'editPassword'])->name('admin.profile.password.edit');
    Route::put('/admin/profile/password', [\App\Http\Controllers\Admin\DashboardController::class, 'updatePassword'])->name('admin.profile.password.update');

});

// API routes (session authenticated)
Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/assets', [ApiAssetController::class, 'index']);
    Route::get('/balances', [ApiBalanceController::class, 'index']);
    Route::get('/transactions', [ApiTransactionController::class, 'index']);
    Route::post('/withdrawals', [ApiTransactionController::class, 'store']);
    Route::post('/deposits', [ApiTransactionController::class, 'storeDeposit']);
});

// Maintenance: create the public/storage symlink (admin only)
Route::get('/foo', function () {
    try {
        Artisan::call('storage:link', ['--force' => true]);

        return true;
    } catch (\Throwable $e) {
        return false;
    }
})->name('admin.storage.link');
