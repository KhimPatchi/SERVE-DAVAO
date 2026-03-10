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
use App\Http\Controllers\Verify\OrganizerVerificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;

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

// Protected routes (auth required) - ALL PROTECTED ROUTES GO HERE
Route::middleware(['auth', 'verified', 'prevent-back-history'])->group(function () { // ← ADD 'prevent-back-history' HERE
    // Dashboard - PROTECTED
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
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
    
    // Organizer Verification Routes (automated - no admin approval needed)
    Route::prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/verification', [OrganizerVerificationController::class, 'create'])->name('verification.create');
        Route::post('/verification', [OrganizerVerificationController::class, 'store'])->name('verification.store');
        Route::get('/verification/status', [OrganizerVerificationController::class, 'status'])->name('verification.status');
    });
    
    // ALL EVENT ROUTES - PROTECTED
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // AI Recommendation routes - PROTECTED
    Route::get('/events/api/recommendations', [EventController::class, 'getRecommendations'])->name('events.recommendations');
    Route::get('/events/{event}/recommended-volunteers', [EventController::class, 'getRecommendedVolunteers'])->name('events.recommended-volunteers');
    
    // Feedback routes - PROTECTED
    Route::get('/events/{event}/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/events/{event}/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/events/{event}/feedback/stats', [FeedbackController::class, 'stats'])->name('feedback.stats');
    
    // Event Suggestions routes - PROTECTED
    Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('/suggestions/create', [SuggestionController::class, 'create'])->name('suggestions.create');
    Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
    Route::post('/suggestions/{suggestion}/vote', [SuggestionController::class, 'vote'])->name('suggestions.vote');
    Route::get('/suggestions/popular', [SuggestionController::class, 'popular'])->name('suggestions.popular');
    Route::put('/suggestions/{suggestion}/status', [SuggestionController::class, 'updateStatus'])->name('suggestions.update-status');

    // Polls routes - PROTECTED
    Route::get('/polls', [\App\Http\Controllers\PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/create', [\App\Http\Controllers\PollController::class, 'create'])->name('polls.create');
    Route::post('/polls', [\App\Http\Controllers\PollController::class, 'store'])->name('polls.store');
    Route::get('/polls/{poll}', [\App\Http\Controllers\PollController::class, 'show'])->name('polls.show');
    Route::post('/polls/{poll}/vote', [\App\Http\Controllers\PollController::class, 'vote'])->name('polls.vote');
    Route::put('/polls/{poll}/status', [\App\Http\Controllers\PollController::class, 'updateStatus'])->name('polls.status');

    
    // Volunteer Preferences routes - PROTECTED
    Route::get('/profile/preferences', [\App\Http\Controllers\Verify\ProfileController::class, 'editPreferences'])->name('profile.preferences');
    Route::post('/profile/preferences', [\App\Http\Controllers\Verify\ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    Route::get('/api/preferences/popular-tags', [\App\Http\Controllers\Verify\ProfileController::class, 'getPopularTags'])->name('api.preferences.tags');
    
    // Volunteer registration routes - PROTECTED
    Route::post('/events/{event}/join', [EventVolunteerController::class, 'join'])->name('events.join');
    Route::delete('/events/{event}/leave', [EventVolunteerController::class, 'leave'])->name('events.leave');

    // ─── QR Ticket (Volunteer) ───────────────────────────────────────────────
    // Returns QR code JSON for the volunteer's check-in ticket modal
    Route::get('/events/{event}/ticket', [EventVolunteerController::class, 'ticket'])->name('events.ticket');

    // ─── QR Attendance (Organizer) ───────────────────────────────────────────
    Route::prefix('organizer')->name('organizer.')->group(function () {
        // Scanner page — organizer views live check-in list and camera
        Route::get('/events/{event}/scan', [\App\Http\Controllers\Attendance\AttendanceController::class, 'scanView'])
            ->name('attendance.scan');

        // End event — marks no-shows, completes event, fires Reverb broadcast
        Route::post('/events/{event}/end', [\App\Http\Controllers\Attendance\AttendanceController::class, 'endEvent'])
            ->name('attendance.end');
    });

    // Signed check-in endpoint — URL is embedded in the volunteer's QR code
    // The `signed` middleware validates the URL signature before the controller runs
    Route::get('/organizer/events/{event}/checkin/{volunteer}',
        [\App\Http\Controllers\Attendance\AttendanceController::class, 'checkin'])
        ->name('attendance.checkin')
        ->middleware('signed');
    
    // MESSAGING ROUTES - PROTECTED
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ConversationController::class, 'index'])->name('index');
        Route::get('/start/{user}', [\App\Http\Controllers\ConversationController::class, 'startDirect'])->name('start-direct');
        Route::get('/event/{event}', [\App\Http\Controllers\ConversationController::class, 'startEventGroup'])->name('start-event');
        Route::get('/search', [\App\Http\Controllers\ConversationController::class, 'search'])->name('search');
        Route::get('/{conversation}', [\App\Http\Controllers\ConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/mark-read', [\App\Http\Controllers\ConversationController::class, 'markAsRead'])->name('mark-read');
        
        // Message operations
        Route::post('/{conversation}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('send');
        Route::delete('/message/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('delete');
    });
});

/// Enhanced logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    // Clear all session data and cache
    session()->flush();
    
    return redirect('/')->withHeaders([
        'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, private',
        'Pragma' => 'no-cache',
        'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
    ]);
})->middleware('auth')->name('logout');

// Contact routes
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/test-simple-contact', function() {
    try {
        $request = new Illuminate\Http\Request([
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com', 
            'subject' => 'Test',
            'message' => 'Test message'
        ]);
        
        $controller = new App\Http\Controllers\ContactController();
        return $controller->submit($request);
        
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Custom login routes that use our custom LoginController
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// KEEP THESE - They're safe and won't conflict:
Route::get('/.env', function () {
    abort(404, 'Page not found');
});

Route::get('/storage/{any}', function () {
    abort(404, 'Page not found');
})->where('any', '.*');

Route::get('/vendor/{any}', function () {
    abort(404, 'Page not found');
})->where('any', '.*');

// KEEP these framework file blocks:
Route::get('/artisan', function () {
    abort(404, 'Page not found');
});

Route::get('/composer.json', function () {
    abort(404, 'Page not found');
});

Route::get('/package.json', function () {
    abort(404, 'Page not found');
});


// Laravel default auth routes
require __DIR__.'/auth.php';