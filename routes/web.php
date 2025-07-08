<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Posts\Create;
use App\Livewire\Posts\Edit;
use App\Livewire\Posts\Index;
use App\Livewire\Posts\Reviews;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::post('/user/review', [HomeController::class, 'reviewStore'])->middleware(['auth', 'verified']);

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');
Route::get('dashboard', function () {
    $reviews = Post::ownedBy(auth()->user()->id)
        ->with(['reviews.user'])
        ->inRandomOrder()
        ->paginate(18);

    return view('dashboard', [
        'reviews' => $reviews,
    ]);
})->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth', 'verified', 'multiRole:admin,creator'])
    ->prefix('posts')
    ->name('posts.')
    ->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/create', Create::class)->name('create');
        Route::get('/{post}/edit', Edit::class)->name('edit');
        Route::get('/{post}/reviews', Reviews::class)->name('review');
    });

require __DIR__ . '/auth.php';
