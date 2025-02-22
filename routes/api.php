<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\EmpClassificationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\LASourceDataController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveRequestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/upload-signature', [AuthController::class,"uploadSignature"]);
Route::resource('/users', AuthController::class);

Route::get('/position-list', [PositionController::class, 'positionList']);
Route::resource('/position', PositionController::class);
Route::get('/office-list', [OfficeController::class, 'officeList']);
Route::resource('/office', OfficeController::class);
Route::get('/leveling', [EmpClassificationController::class, 'leveling']);
Route::get('/leveling/{id}', [EmpClassificationController::class, 'levelid']);
Route::post('/employees', [EmployeeController::class, 'storeEmployee']);
Route::put('/employees-edit/{id}', [EmployeeController::class, 'updateEmployee']);
Route::put('/employees-change-password/{id}', [EmployeeController::class, 'changePasswordEmployee']);
Route::get('/employees-list', [EmployeeController::class, 'employeeList']);
Route::get('/employee', [EmployeeController::class,'employee']);

Route::post('/setup-initial', [SetupController::class,'addInitialAppproval']);
Route::get('/setup-initial-list/{id}', [SetupController::class,'listInitial']);
Route::delete('/setup-initial-delete/{id}', [SetupController::class,'delete']);

Route::post('/setup-final', [SetupController::class,'addFinalAppproval']);
Route::get('/setup-final-list/{id}', [SetupController::class,'listFinal']);
Route::delete('/setup-final-delete/{id}', [SetupController::class,'deleteFinal']);

Route::get('/leave', [SetupController::class,'leave']);

Route::post('/setup-credit', [SetupController::class,'addCredit']);
Route::get('/setup-credit-list/{id}', [SetupController::class,'listCredit']);
Route::put('/setup-credit-edit/{id}', [SetupController::class,'editCredit']);

Route::post('/setup-school-year', [SetupController::class,'schoolyearSetup']);
Route::put('/setup-school-year/{id}', [SetupController::class,'schoolyearSetupEdit']);
Route::get('/school-year-list', [SetupController::class,'schoolyearSetupList']);
Route::get('/school-year-status/{id}', [SetupController::class,'schoolyearSetupStatus']);

Route::get('/source-leave', [LASourceDataController::class, 'leave']);
Route::get('/source-initial', [LASourceDataController::class, 'initialApproval']);
Route::get('/source-final', [LASourceDataController::class, 'finalApproval']);

Route::get('/my-leave', [LeaveApplicationController::class,'myLeave']);
Route::resource('/leave-application', LeaveApplicationController::class);

Route::get('/initial-request',[LeaveRequestController::class,'initialRequest']);
Route::get('/final-request',[LeaveRequestController::class,'finalRequest']);
Route::post('/initial-approved',[LeaveRequestController::class,'initialApproved']);
Route::post('/final-approved',[LeaveRequestController::class,'finalApproved']);
Route::post('/disapproved',[LeaveRequestController::class,'disapproved']);






