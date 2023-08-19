<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Owner\AuthController;
use App\Http\Controllers\Owner\OwnerEmployeeController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('auth')->group(function () {
    //get user
    Route::get('user',[AuthController::class, 'getUser'])->middleware('auth:api');
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    //logout
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    //add-employee
    Route::post('/add-employee', [AuthController::class, 'addEmployee'])->middleware(['auth:api', 'usertype:owner']);
});

Route::prefix('employee')->group(function () {
    //employeeList
    Route::get('/list', [OwnerEmployeeController::class, 'employeeList'])->middleware(['auth:api', 'usertype:owner']);
    //checkin
    Route::post('/checkin', [OwnerEmployeeController::class, 'checkIn'])->middleware(['auth:api', 'usertype:employee']);
    //start-break
    Route::post('/start-break', [OwnerEmployeeController::class, 'startBreak'])->middleware(['auth:api', 'usertype:employee']);
    //end-break
    Route::post('/end-break', [OwnerEmployeeController::class, 'endBreak'])->middleware(['auth:api', 'usertype:employee']);
    //checkout
    Route::post('/checkout', [OwnerEmployeeController::class, 'checkOut'])->middleware(['auth:api', 'usertype:employee']);
    //report-list any specific date filter
    Route::get('/report', [OwnerEmployeeController::class, 'employeeReportList'])->middleware(['auth:api', 'usertype:owner']);
    //Individual report-list
    Route::get('/individual', [OwnerEmployeeController::class, 'individualReportList'])->middleware(['auth:api', 'usertype:owner']);
    //generate pdf for individual employee
    Route::get('individual-pdf', [OwnerEmployeeController::class, 'generateIndividualPDF'])->middleware(['auth:api', 'usertype:owner']);
});






// Route::middleware('owner')->group(function () {
//     //Employee add
//     Route::get('/addemployee',[EmployeeController::class,'addEmployee'])->name('addemployee');
//     Route::post('/employee/store',[EmployeeController::class,'storeEmployee'])->name('employee.store');
//     Route::get('/employeeList',[EmployeeController::class,'employeeList'])->name('employeelist');
//     //Employee report
//      Route::get('/employee-reports',[EmployeeReportController::class,'index'])->name('employee.reports.index');
//      Route::get('/single-employee-reports/{id}',[EmployeeReportController::class,'ParticularemployeeList'])->name('single-employee-reports');
// });
