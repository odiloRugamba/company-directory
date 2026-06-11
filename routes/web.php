<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ApplicationAdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\IncubatorAdminController;
use App\Http\Controllers\Admin\StartupAdminController;
use App\Http\Controllers\Incubator\ApplicationsController as IncubatorApplications;
use App\Http\Controllers\Incubator\DashboardController as IncubatorDashboard;
use App\Http\Controllers\Incubator\ProfileController as IncubatorProfile;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Public\DirectoryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Setup\SetupController;
use App\Http\Controllers\Startup\ApplicationsController as StartupApplications;
use App\Http\Controllers\Startup\DashboardController as StartupDashboard;
use App\Http\Controllers\Startup\ProfileController as StartupProfile;
use Illuminate\Support\Facades\Route;

// Dashboard redirect — routes based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect('/login');
    if (!$user->hasProfile()) return redirect('/setup');
    return match($user->role) {
        \App\Enums\Role::Admin => redirect()->route('admin.dashboard'),
        \App\Enums\Role::Incubator => redirect()->route('incubator.dashboard'),
        \App\Enums\Role::Startup => redirect()->route('startup.dashboard'),
    };
})->middleware('auth')->name('dashboard'); // Used by Breeze post-login redirect

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory');
Route::get('/incubators/{slug}', [DirectoryController::class, 'showIncubator'])->name('incubators.show');
Route::get('/startups/{slug}', [DirectoryController::class, 'showStartup'])->name('startups.show');

// Auth routes (Breeze)
require __DIR__ . '/auth.php';

// Profile setup (auth required, before profile check)
Route::middleware(['auth'])->group(function () {
    Route::get('/setup', [SetupController::class, 'show'])->name('setup');
    Route::post('/setup/incubator', [SetupController::class, 'storeIncubator'])->name('setup.incubator');
    Route::post('/setup/startup', [SetupController::class, 'storeStartup'])->name('setup.startup');
});

// Notifications
Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
    ->middleware('auth')
    ->name('notifications.read-all');

// Admin routes
Route::middleware(['auth', 'profile.complete', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/incubators', [IncubatorAdminController::class, 'index'])->name('incubators');
        Route::patch('/incubators/{incubator}/verify', [IncubatorAdminController::class, 'verify'])->name('incubators.verify');
        Route::patch('/users/{user}/deactivate', [IncubatorAdminController::class, 'deactivate'])->name('users.deactivate');
        Route::get('/startups', [StartupAdminController::class, 'index'])->name('startups');
        Route::get('/applications', [ApplicationAdminController::class, 'index'])->name('applications');
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
    });

// Incubator routes
Route::middleware(['auth', 'profile.complete', 'role:incubator'])
    ->prefix('dashboard')
    ->name('incubator.')
    ->group(function () {
        Route::get('/overview', [IncubatorDashboard::class, 'index'])->name('dashboard');
        Route::get('/applications', [IncubatorApplications::class, 'index'])->name('applications');
        Route::get('/profile', [IncubatorProfile::class, 'edit'])->name('profile');
        Route::put('/profile', [IncubatorProfile::class, 'update'])->name('profile.update');
    });

// Startup routes
Route::middleware(['auth', 'profile.complete', 'role:startup'])
    ->prefix('dashboard')
    ->name('startup.')
    ->group(function () {
        Route::get('/startup', [StartupDashboard::class, 'index'])->name('dashboard');
        Route::get('/applications', [StartupApplications::class, 'index'])->name('applications');
        Route::get('/apply/{slug}', [StartupApplications::class, 'create'])->name('apply');
        Route::post('/apply', [StartupApplications::class, 'store'])->name('apply.store');
        Route::get('/profile', [StartupProfile::class, 'edit'])->name('profile');
        Route::put('/profile', [StartupProfile::class, 'update'])->name('profile.update');
    });
