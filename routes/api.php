<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Api\ParticipantResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/verify', [App\Http\Controllers\Api\AuthController::class, 'verify']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

Route::prefix('csf')->controller(App\Http\Controllers\Api\CsfController::class)->group(function () {
    Route::get('/questions', 'questions');
    Route::post('/public', 'public');
    Route::post('/fb', 'fb');
});

Route::get('/participant', function (Request $request) {
     return new ParticipantResource(
        $request->user()->load(['detail.sex', 'detail.type'])
    );
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('sessions')->controller(App\Http\Controllers\Api\SessionController::class)->group(function () {
        Route::post('/attendance', 'attendance');
        Route::post('/question', 'question');
        Route::post('/registration', 'registration');
        Route::post('/cancel', 'cancel');
    });

    Route::prefix('exhibitors')->controller(App\Http\Controllers\Api\ExhibitorController::class)->group(function () {
        Route::post('/attendance', 'attendance');
        Route::post('/vote', 'vote');
        Route::post('/review', 'review');
    });

    Route::prefix('hotels')->controller(App\Http\Controllers\Api\HotelController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('csf')->controller(App\Http\Controllers\Api\CsfController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/session', 'session');
        Route::post('/exhibitor', 'exhibitor');
    });

    Route::post('/avatar', [App\Http\Controllers\Api\AvatarController::class, 'avatar']);
    Route::post('/signature', [App\Http\Controllers\Api\AvatarController::class, 'signature']);
    Route::post('/completed', [App\Http\Controllers\Api\AvatarController::class, 'completed']);
    Route::get('/dashboard', [App\Http\Controllers\Api\DashboardController::class, 'index']);

    Route::post('/profile', [App\Http\Controllers\Api\ParticipantController::class, 'profile']);
});