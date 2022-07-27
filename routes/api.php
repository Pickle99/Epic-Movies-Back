<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware('guest')->group(function () {
	Route::post('register', [RegisterController::class, 'createUser'])->name('register');
	Route::post('successfully-verified/{token}', [RegisterController::class, 'verifyEmail'])->name('user.verify');

	Route::post('login', [AuthController::class, 'login'])->name('login');

	Route::post('forgot-password', [ResetPasswordController::class, 'resetPassword'])->name('password.email');
	Route::post('reset-password/{token}', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

	Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
	Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth:api')->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');

	Route::get('user/{user}', [UserController::class, 'show'])->middleware('auth:api');
	Route::post('user/{user}/update', [UserController::class, 'update'])->middleware('auth:api');

	Route::post('quote/{quote}/add-comment', [CommentController::class, 'store'])->name('comment.store');

	Route::get('quote/{quote}/add-like', [LikeController::class, 'store'])->name('like.store');

	Route::get('genres', [GenreController::class, 'showGenres'])->name('genres.get');

	Route::controller(MovieController::class)->group(function () {
		Route::post('movies', 'store')->name('movies.store');
		Route::get('all-movies', 'showAllMovies')->name('movies.all_show');
		Route::get('user-movies', 'showUserMovies')->name('user.movies');
		Route::get('movie-description/{movie}', 'showMovieDescription')->name('movie.description');
		Route::get('movie/{movie}', 'showMovie')->name('show.movie');
		Route::get('movie/{movie}/genres', 'showMovieWithGenres')->name('movie.genres_show');
		Route::post('movie/{movie}/update', 'update')->name('movie.update');
		Route::delete('movie/{movie}/delete', 'destroy')->name('movie.destroy');
	});

	Route::controller(QuoteController::class)->group(function () {
		Route::post('movie/{movie}/quote', 'store')->name('quote.store');
		Route::get('quote/{quote}', 'showQuote')->name('quote.create');
		Route::post('quote/{quote}/update', 'update')->name('quote.update');
		Route::delete('quote/{quote}/delete', 'destroy')->name('quote.destroy');
		Route::get('feed', 'showPaginatedQuotes')->name('all.quotes_show');
		Route::get('all-quotes', 'showAllQuotes')->name('quotes.all_show');
		Route::post('add-quote', 'storeWriteQuote')->name('write.quote_store');
	});

	Route::controller(NotificationController::class)->group(function () {
		Route::get('notifications/mark-all-as-read', 'markAsAllRead')->name('notification.all-read');
		Route::post('notification/{notification}/mark-single-as-read', 'markSingleAsRead')->name('notification.single-read');
		Route::get('notifications', 'show')->name('notifications.show');
	});
});
