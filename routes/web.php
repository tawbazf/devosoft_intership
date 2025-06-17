<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/welcome', function () {
    if (auth()->check()) {
        return redirect()->route('videos.index');
    }
    return view('welcome');
})->name('welcome');
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('videos.index')); // Redirect to videos after login
    Route::get('/videos', [VideoController::class, 'indexView'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'showView'])->name('videos.show');
    Route::get('/play/{video}', [VideoController::class, 'playView'])->name('videos.play');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', fn() => view('admin.users'))->name('admin.users');
    });
});
?>