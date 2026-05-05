<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/businesses', [AdminController::class, 'businesses'])->name('businesses');
        Route::patch('/businesses/{business}/approve', [AdminController::class, 'approveBusiness'])->name('businesses.approve');
        Route::patch('/businesses/{business}/suspend', [AdminController::class, 'suspendBusiness'])->name('businesses.suspend');
        Route::patch('/businesses/{business}/featured', [AdminController::class, 'toggleFeatured'])->name('businesses.featured');

        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::patch('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
        Route::patch('/reviews/{review}/reject', [AdminController::class, 'rejectReview'])->name('reviews.reject');
    });

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Business Search (Livewire page)
Route::get('/search', function () {
    return view('search');
})->name('businesses.search');

// Business Profiles
Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::get('/create', [BusinessController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [BusinessController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/{business}', [BusinessController::class, 'show'])->name('show');
    Route::get('/{business}/edit', [BusinessController::class, 'edit'])->name('edit')->middleware('auth');
    Route::put('/{business}', [BusinessController::class, 'update'])->name('update')->middleware('auth');
    Route::post('/{business}/favorite', [BusinessController::class, 'toggleFavorite'])
        ->name('favorite')->middleware('auth');
});

// Reviews
Route::prefix('reviews')->name('reviews.')->middleware('auth')->group(function () {
    Route::get('/{business}/create', [ReviewController::class, 'create'])->name('create');
    Route::post('/{business}', [ReviewController::class, 'store'])->name('store');
    Route::post('/{review}/respond', [ReviewController::class, 'respond'])->name('respond');
    Route::post('/{review}/helpful', [ReviewController::class, 'helpful'])->name('helpful');
});

// Service Requests
Route::prefix('service-requests')->name('service-requests.')->middleware('auth')->group(function () {
    Route::get('/', [ServiceRequestController::class, 'index'])->name('index');
    Route::get('/create', [ServiceRequestController::class, 'create'])->name('create');
    Route::post('/', [ServiceRequestController::class, 'store'])->name('store');
    Route::get('/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('show');
    Route::delete('/{serviceRequest}', [ServiceRequestController::class, 'destroy'])->name('destroy');
});

// Provider Dashboard (Livewire)
Route::get('/dashboard/provider', function () {
    /** @var User|null $user */
    $user = Auth::user();

    abort_unless($user && $user->isProvider(), 403);

    return view('dashboard.provider');
})->name('dashboard.provider')->middleware('auth');

// Auth (using Breeze/Jetstream scaffolding — routes registered by their package)
require __DIR__ . '/auth.php';