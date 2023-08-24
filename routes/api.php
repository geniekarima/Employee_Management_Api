<?php

use App\Http\Controllers\Employee\EmployeeProfileController;
use Illuminate\Http\Request;
use App\Http\Controllers\OwnerEmployee\AuthController;
use App\Http\Controllers\OwnerEmployee\OwnerEmployeeController;
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
    //checkin
    Route::post('/checkin', [OwnerEmployeeController::class, 'checkIn'])->middleware(['auth:api', 'usertype:employee']);
    //start-break
    Route::post('/start-break', [OwnerEmployeeController::class, 'startBreak'])->middleware(['auth:api', 'usertype:employee']);
    //end-break
    Route::post('/end-break', [OwnerEmployeeController::class, 'endBreak'])->middleware(['auth:api', 'usertype:employee']);
    //checkout
    Route::post('/checkout', [OwnerEmployeeController::class, 'checkOut'])->middleware(['auth:api', 'usertype:employee']);

    Route::prefix('profile')->group(function() {
        //update employee profile
        Route::post('/update', [EmployeeProfileController::class, 'updateEmployeeProfile'])->middleware(['auth:api', 'usertype:employee']);

      });
});

Route::prefix('owner')->group(function () {
    //employeeList
    Route::get('/list', [OwnerEmployeeController::class, 'employeeList'])->middleware(['auth:api', 'usertype:owner']);
    //report-list all employee
    Route::get('/report', [OwnerEmployeeController::class, 'employeeReportList'])->middleware(['auth:api', 'usertype:owner']);

    Route::prefix('employee-profile')->group(function() {
        //edit employee's profile
        Route::post('/edit', [EmployeeProfileController::class, 'ownerEmployeesProfileUpdate'])->middleware(['auth:api', 'usertype:owner']);

      });

});



// Route::middleware('owner')->group(function () {

// });
