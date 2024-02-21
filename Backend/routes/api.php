<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Auth;

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
Route::post('/send-otp-email', [AuthController::class, 'sendOtpEmail']);

Route::post('/login', [AuthController::class, 'login']); 
Route::post('/register', [AuthController::class, 'register']); 
Route::get('/students', [StudentController::class, 'index']); 
Route::get('/event-count-by-month-current-year', [EventsController::class, 'getCountByMonthCurrentYear']);

Route::post('/archive-students', [StudentController::class, 'archiveStudents']);

Route::group(['middleware'=> ['auth:sanctum', 'restrictRole:user']], function(){
    Route::get('/students', [StudentController::class, 'index']); 
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::put('/update-password', [AuthController::class, 'changePassword']); 
});

Route::group(['middleware'=> ['auth:sanctum', 'restrictRole:admin']], function(){
    Route::delete('/students/{id}', [StudentController::class, 'destroy']); 
    Route::get('/students', [StudentController::class, 'index']); 
    Route::get('/get-archived-students', [StudentController::class, 'getArchivedStudents']); 
    Route::put('/update-profile', [AuthController::class, 'update']); 
    Route::put('/update-password', [AuthController::class, 'changePassword']); 
    Route::get('/events-get-all', [EventsController::class, 'index']);
    Route::put('/students/{id}', [StudentController::class, 'update']); 
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::post('/upload-excel', [StudentController::class, 'uploadExcel']);
    Route::put('/update-status', [StudentController::class, 'updateStatus']);
    Route::get('/distinct-semesters-and-years', [StudentController::class, 'listDistinctSemestersAndYears']);
    Route::post('/events-export-excel', [EventsController::class, 'exportExcel']);
    Route::post('/check-existence', [AuthController::class, 'checkExistence']);
    Route::post('/export-to-pdf', [EventsController::class, 'exportToPdfApi']);
    Route::get('/events-get-all', [EventsController::class, 'index']);
    Route::post('/store-event', [EventsController::class, 'store']);
    Route::get('/get-archived-events', [EventsController::class, 'getArchived']);
    Route::put('/update-event/{id}', [EventsController::class, 'update']);
    Route::get('/show-event/{id}', [EventsController::class, 'show']);
    Route::post('/transactions-export-excel', [TransactionsController::class, 'exportExcel']);
    Route::post('/store-transaction', [TransactionsController::class, 'store']);
    Route::get('/transactions-get-all', [TransactionsController::class, 'index']);
    Route::put('/update-transaction/{id}', [TransactionsController::class, 'update']);
    Route::get('/transactions-display-report/{event_id}/{academic_year}/{semester}', [TransactionsController::class, 'displayReport']);
    Route::get('/students/{student_number}', [StudentController::class, 'show']); 
    Route::get('/academic-year/{academic_year}', [StudentController::class, 'getDistinctAcademicYears']); 
    Route::get('/semester/{semester}', [StudentController::class, 'getDistinctSemesters']); 
    Route::get('/students/search/{name}', [StudentController::class, 'search']);
    Route::get('/enrolled-students-count', [StudentController::class, 'getEnrolledStudentsCount']);
    Route::get('/get-archived-transactions', [TransactionsController::class, 'getArchived']);
    Route::post('/transactions-export-pdf', [TransactionsController::class, 'exportToPdfApi']);
    Route::post('/check-transaction', [TransactionsController::class, 'check']);
    Route::get('/show-transaction/{event_id}', [TransactionsController::class, 'show']);
    Route::get('/total-paid-and-not-paid', [TransactionsController::class, 'getTotalPaidAndNotPaid']);
    Route::get('/transaction-count-by-month-current-year', [TransactionsController::class, 'getCountByMonthCurrentYear']);
    Route::get('/registered-students-count', [AuthController::class, 'getRegisteredStudentsCount']);
    Route::get('/active-events-count', [EventsController::class, 'getActiveEventsCount']);
});