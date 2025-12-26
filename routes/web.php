<?php

use App\Http\Controllers\VelzonRoutesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search']);

Route::middleware(['role:Human Resource Officer'])->group(function () {
    Route::resource('/employees', App\Http\Controllers\HumanResource\EmployeeController::class);
});

Route::middleware(['role:Administrator'])->group(function () {
    Route::resource('/users', App\Http\Controllers\Executive\UserController::class);
    Route::resource('/references', App\Http\Controllers\Executive\ReferenceController::class);
    Route::resource('/signatories', App\Http\Controllers\Executive\SignatoryController::class);

    Route::get('/rekognition/test', [App\Http\Controllers\Executive\RekognitionController::class, 'test']);
    Route::get('/rekognition/check', [App\Http\Controllers\Executive\RekognitionController::class, 'check']);
    Route::get('/rekognition/delete', [App\Http\Controllers\Executive\RekognitionController::class, 'delete']);
    Route::get('/rekognition/create', [App\Http\Controllers\Executive\RekognitionController::class, 'create']);
    Route::get('/rekognition/search', [App\Http\Controllers\Executive\RekognitionController::class, 'search']);
});

require __DIR__.'/auth.php';
