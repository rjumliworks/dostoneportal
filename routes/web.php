<?php
use Illuminate\Support\Facades\Route;
use App\Events\SessionEvent;
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


Route::domain('rstw2026.' . config('app.app_host'))->as('rstw2026.')->group(function () {
    Route::get('/', [App\Http\Controllers\Event\PublicController::class, 'index']);
    Route::get('/registration', [App\Http\Controllers\Event\PublicController::class, 'registration']);
    Route::get('/registration/success', [App\Http\Controllers\Event\PublicController::class, 'successGeneral'])->name('success.general');
    Route::get('/registration/{key}', [App\Http\Controllers\Event\PublicController::class, 'register']);
    Route::get('/registration/bPZBcQqTBHnfTUMG4qPQvA/{key}', [App\Http\Controllers\Event\PublicController::class, 'registervip']);
    Route::get('/registration/bPZBcQqTBHnfTUMG4qPQvA/{key}/success', [App\Http\Controllers\Event\PublicController::class, 'success'])->name('successvip');
    Route::get('/registration/{key}/success', [App\Http\Controllers\Event\PublicController::class, 'success'])->name('success');
    Route::get('/search', [App\Http\Controllers\Event\PublicController::class, 'search']);
    Route::get('/session/{key}', [App\Http\Controllers\Event\SessionController::class, 'view']);
    Route::post('/recognize', [App\Http\Controllers\Event\PublicController::class, 'recognize']);
    Route::post('/register', [App\Http\Controllers\Event\RegistrationController::class, 'store']);
    Route::get('/opening', [App\Http\Controllers\Event\PublicController::class, 'opening']);
    Route::get('/qrcode', [App\Http\Controllers\Event\PublicController::class, 'qrcode']);
    Route::post('/check', [App\Http\Controllers\Event\VipController::class, 'check']);
    Route::get('/scanner', [App\Http\Controllers\Event\VipController::class, 'scanner']);
    Route::get('/vipregistrationkrad', [App\Http\Controllers\Event\VipController::class, 'registration']);
    Route::post('/register-vip', [App\Http\Controllers\Event\VipController::class, 'store']);

});

Route::domain('attendance.' . config('app.app_host'))->as('attendance.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\AttendanceController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Public\AttendanceController::class, 'store']);
    Route::post('/recognize', [App\Http\Controllers\Public\AttendanceController::class, 'recognize']);
    Route::get('/{station}', [App\Http\Controllers\Public\AttendanceController::class, 'show'])->middleware('attendance')->name('attendance.station');
    
});

Route::domain('wfh.' . config('app.app_host'))->as('wfh.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\WfhController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Public\WfhController::class, 'store']);
    Route::post('/recognize', [App\Http\Controllers\Public\WfhController::class, 'recognize']);
});

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search']);
Route::get('/dropdowns', [App\Http\Controllers\SearchController::class, 'dropdowns']);

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
     Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
    
    Route::resource('/dtr', App\Http\Controllers\Portal\DtrController::class);
    Route::resource('/requests', App\Http\Controllers\Portal\RequestController::class);
    Route::resource('/whereabouts', App\Http\Controllers\Portal\WhereaboutController::class);
    Route::resource('/schedules', App\Http\Controllers\Portal\ScheduleController::class);
});

Route::middleware(['role:Asset Management Officer'])->group(function () {
    Route::resource('/buildings', App\Http\Controllers\Assets\BuildingController::class);
    Route::resource('/equipments', App\Http\Controllers\Assets\EquipmentController::class);
    Route::resource('/vehicles', App\Http\Controllers\Assets\VehicleController::class);
});

Route::middleware(['role:Document Management Officer'])->group(function () {
    Route::resource('/activities', App\Http\Controllers\Trace\EventController::class);
    Route::resource('/signatories', App\Http\Controllers\Trace\SignatoryController::class);

});

Route::middleware(['role:Event Manager'])->group(function () {
    Route::resource('/events', App\Http\Controllers\Event\EventController::class);
    Route::resource('/exhibits', App\Http\Controllers\Event\ExhibitController::class);
    Route::resource('/participants', App\Http\Controllers\Event\ParticipantController::class);
});

Route::middleware(['role:Event Manager,Session Manager'])->group(function () {
    Route::resource('/sessions', App\Http\Controllers\Event\SessionController::class);
});

Route::resource('/projects', App\Http\Controllers\Others\ProjectController::class);
Route::resource('/budgets', App\Http\Controllers\Others\BudgetController::class);

Route::middleware(['role:Human Resource Officer'])->group(function () {
    Route::resource('/humanresource', App\Http\Controllers\HumanResource\DashboardController::class);
    Route::get('/tardiness', [App\Http\Controllers\HumanResource\DashboardController::class, 'tardiness']);
    Route::get('/absences', [App\Http\Controllers\HumanResource\DashboardController::class, 'absences']);
    Route::resource('/employees', App\Http\Controllers\HumanResource\EmployeeController::class);
    Route::resource('/dtrs', App\Http\Controllers\HumanResource\DtrController::class);
    Route::resource('/calendar', App\Http\Controllers\HumanResource\CalendarController::class);
    Route::resource('/payroll', App\Http\Controllers\HumanResource\PayrollController::class);
    Route::get('/payroll/{type}/{code}', [App\Http\Controllers\HumanResource\PayrollController::class, 'view']);
    Route::resource('/credits', App\Http\Controllers\HumanResource\CreditController::class);
    Route::resource('/visitors', App\Http\Controllers\HumanResource\VisitorController::class);
});

Route::resource('/surveys', App\Http\Controllers\HumanResource\SurveyController::class);
Route::resource('/approvals', App\Http\Controllers\Portal\ApprovalController::class);

Route::middleware(['role:Administrator'])->group(function () {
    Route::resource('/users', App\Http\Controllers\Executive\UserController::class);
    Route::resource('/shifts', App\Http\Controllers\Executive\ShiftController::class);
    Route::resource('/references', App\Http\Controllers\Executive\ReferenceController::class);
    // Route::get('/rekognition/test', [App\Http\Controllers\Executive\RekognitionController::class, 'test']);
    // Route::get('/rekognition/check', [App\Http\Controllers\Executive\RekognitionController::class, 'check']);
    // Route::get('/rekognition/delete', [App\Http\Controllers\Executive\RekognitionController::class, 'delete']);
    // Route::get('/rekognition/create', [App\Http\Controllers\Executive\RekognitionController::class, 'create']);
    // Route::get('/rekognition/search', [App\Http\Controllers\Executive\RekognitionController::class, 'search']);
    // Route::get('/rekognition/collection/{id}', [App\Http\Controllers\Executive\RekognitionController::class, 'deleteCollection']);
    Route::get('/rekognition/collection/{id}/faces', [App\Http\Controllers\Executive\RekognitionController::class, 'listFaces']);
    Route::get('/rekognition/collection/{id}/faces/orphaned', [App\Http\Controllers\Executive\RekognitionController::class, 'orphanFaces']);
    Route::get('/rekognition/collection/{id}/faces/orphaned/delete', [App\Http\Controllers\Executive\RekognitionController::class, 'deleteOrphanFaces']);
    Route::get('/rekognition/collection/{id}/face/{faceId}/delete', [App\Http\Controllers\Executive\RekognitionController::class, 'deleteFace']);
});

Route::get('/key-officials', [App\Http\Controllers\Public\InfoController::class, 'keyofficials']);
Route::get('/mailing', [App\Http\Controllers\Public\InfoController::class, 'mailing']);
Route::get('/mailing/test', [App\Http\Controllers\Public\InfoController::class, 'mailingTest']);
require __DIR__.'/auth.php';
