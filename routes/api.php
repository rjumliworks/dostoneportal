<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Api\Events\ParticipantResource;

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


Route::post('/login', [App\Http\Controllers\Api\Events\AuthController::class, 'login']);
Route::post('/verify', [App\Http\Controllers\Api\Events\AuthController::class, 'verify']);
Route::post('/register', [App\Http\Controllers\Api\Events\AuthController::class, 'register']);


Route::get('/participant', function (Request $request) {
     return new ParticipantResource(
        $request->user()->load(['detail.sex', 'detail.type'])
    );
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\Api\Events\DashboardController::class, 'index']);

    Route::prefix('sessions')->controller(App\Http\Controllers\Api\Events\SessionController::class)->group(function () {
        Route::post('/attendance', 'attendance');
        Route::post('/question', 'question');
        Route::post('/registration', 'registration');
        Route::post('/cancel', 'cancel');
        Route::post('/feedback', 'feedback');
    });

    Route::prefix('exhibitors')->controller(App\Http\Controllers\Api\Events\ExhibitorController::class)->group(function () {
        Route::post('/attendance', 'attendance');
        Route::post('/vote', 'vote');
        Route::post('/review', 'review');
        Route::post('/feedback', 'feedback');
    });

    Route::prefix('hotels')->controller(App\Http\Controllers\Api\Events\HotelController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::post('/sessions/register-existing', [App\Http\Controllers\Event\RegistrationController::class, 'registerExisting']);

    Route::post('/avatar', [App\Http\Controllers\Api\Events\AvatarController::class, 'avatar']);
    Route::post('/signature', [App\Http\Controllers\Api\Events\AvatarController::class, 'signature']);
    Route::post('/completed', [App\Http\Controllers\Api\Events\AvatarController::class, 'completed']);
    Route::post('/profile', [App\Http\Controllers\Api\Events\ParticipantController::class, 'profile']);
});