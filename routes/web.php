<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Table\UserController;
use App\Http\Controllers\Table\DashboardController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Table\EventVolunteerController;
use App\Http\Controllers\Table\VolunteerController;
use App\Http\Controllers\EventC\EventController;
use App\Http\Controllers\Verify\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Verify\OrganizerVerificationController; // Updated namespace

// Landing Page
Route::get('/', function() {
    $events = \App\Models\Event::with('organizer')
        ->where('status', 'active')
        ->where('date', '>=', now())
        ->orderBy('date', 'asc')
        ->take(6)
        ->get();
        
    return view('landing', compact('events'));
})->name('landing');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Debug routes (remove these in production)
Route::get('/debug-create', function() {
    return "Debug route is working!";
});
    
// Protected routes (auth required) - ALL PROTECTED ROUTES GO HERE
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - PROTECTED
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
// Profile Routes
Route::get('/profile', [\App\Http\Controllers\Verify\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [\App\Http\Controllers\Verify\ProfileController::class, 'update'])->name('profile.update');
    
    // Volunteer Opportunities - PROTECTED
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers');
    
    // Volunteer management routes - PROTECTED
    Route::get('/my-events', [VolunteerController::class, 'myEvents'])->name('volunteers.my-events');
    Route::get('/organized-events', [VolunteerController::class, 'organizedEvents'])->name('volunteers.organized-events');
    Route::get('/events/{event}/volunteers', [VolunteerController::class, 'eventVolunteers'])->name('volunteers.event-volunteers');
    
    // User Management - PROTECTED
    Route::resource('users', UserController::class);
    
    // Volunteer Stats Routes - PROTECTED
    Route::get('/user/volunteer-stats', [UserController::class, 'getUserVolunteerStats'])->name('user.volunteer-stats');
    Route::get('/user/{user}/volunteer-stats', [UserController::class, 'getUserVolunteerStats'])->name('user.volunteer-stats.admin');
    
    // ✅ UPDATED: Organizer Verification Routes with new namespace
    Route::prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/verification', [OrganizerVerificationController::class, 'create'])->name('verification.create');
        Route::post('/verification', [OrganizerVerificationController::class, 'store'])->name('verification.store');
        Route::get('/verification/status', [OrganizerVerificationController::class, 'status'])->name('verification.status');
    });
    
    // ALL EVENT ROUTES - PROTECTED (MOVED INSIDE AUTH)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    
    // Volunteer registration routes - PROTECTED
    Route::post('/events/{event}/join', [EventVolunteerController::class, 'join'])->name('events.join');
    Route::delete('/events/{event}/leave', [EventVolunteerController::class, 'leave'])->name('events.leave');
});

// Admin routes - PROTECTED (requires both auth and admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Event Management
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::post('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::post('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    
    // Organizer Verification Management Routes
    Route::get('/organizer-verifications', [AdminController::class, 'organizerVerifications'])->name('organizer-verifications');
    Route::post('/organizer-verifications/{user}/approve', [AdminController::class, 'approveOrganizer'])->name('organizer-verifications.approve');
    Route::post('/organizer-verifications/{user}/reject', [AdminController::class, 'rejectOrganizer'])->name('organizer-verifications.reject');
});

// Logout route - PROTECTED
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');


// Audit Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Audit Routes
    Route::prefix('audit')->group(function () {
        Route::get('/logs', [App\Http\Controllers\Audit\AuditController::class, 'logs'])->name('admin.audit.logs');
        Route::get('/user-activity', [App\Http\Controllers\Audit\AuditController::class, 'userActivity'])->name('admin.audit.user-activity');
        Route::get('/security', [App\Http\Controllers\Audit\AuditController::class, 'security'])->name('admin.audit.security');
        Route::get('/events', [App\Http\Controllers\Audit\AuditController::class, 'events'])->name('admin.audit.events');
        Route::get('/financial', [App\Http\Controllers\Audit\AuditController::class, 'financial'])->name('admin.audit.financial');
        Route::get('/database', [App\Http\Controllers\Audit\AuditController::class, 'database'])->name('admin.audit.database');
        Route::get('/api', [App\Http\Controllers\Audit\AuditController::class, 'api'])->name('admin.audit.api');
        Route::get('/compliance', [App\Http\Controllers\Audit\AuditController::class, 'compliance'])->name('admin.audit.compliance');
        Route::get('/monitoring', [App\Http\Controllers\Audit\AuditController::class, 'monitoring'])->name('admin.audit.monitoring');
        Route::get('/export', [App\Http\Controllers\Audit\AuditController::class, 'export'])->name('admin.audit.export');
    });
});

// Laravel default auth routes
require __DIR__.'/auth.php';