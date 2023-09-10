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
    Route::get('user', [AuthController::class, 'getUser'])->middleware('auth:api');
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    //logout
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    //add-employee
    Route::post('/add-employee', [AuthController::class, 'addEmployee'])->middleware(['auth:api', 'usertype:owner']);
    //forget password
    Route::post('forget-password', [AuthController::class, 'resendOtp']);
    Route::post('/verify-forget-password', [AuthController::class, 'verifyOtp']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
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

    Route::prefix('profile')->group(function () {
        //update employee profile
        Route::post('/update', [EmployeeProfileController::class, 'updateEmployeeProfile'])->middleware(['auth:api', 'usertype:employee']);

    });
    Route::prefix('task')->group(function () {
        //added task
        Route::post('/add', [OwnerEmployeeController::class, 'addTask'])->middleware(['auth:api', 'usertype:employee']);
        //show auth task list
        Route::get('/list', [OwnerEmployeeController::class, 'authTaskList'])->middleware(['auth:api', 'usertype:employee']);
        //update auth task list
        Route::post('/update', [OwnerEmployeeController::class, 'authTaskUpdate'])->middleware(['auth:api', 'usertype:employee']);
       //delete auth task list
        Route::post('/delete', [OwnerEmployeeController::class, 'authTaskDelete'])->middleware(['auth:api', 'usertype:employee']);

    });
    //auth project assign
    Route::get('/auth-project-list', [OwnerEmployeeController::class, 'authProjectList'])->middleware(['auth:api', 'usertype:employee']);
});

Route::prefix('owner')->group(function () {
    //employeeList
    Route::get('/list', [OwnerEmployeeController::class, 'employeeList'])->middleware(['auth:api', 'usertype:owner']);
    //report-list all employee
    Route::get('/report', [OwnerEmployeeController::class, 'employeeReportList'])->middleware(['auth:api', 'usertype:owner']);
    //add project
    Route::post('/project-add', [OwnerEmployeeController::class, 'addProject'])->middleware(['auth:api', 'usertype:owner']);
    //project list
    Route::get('/project-list', [OwnerEmployeeController::class, 'projectList'])->middleware(['auth:api', 'usertype:owner']);
    //update project
    Route::post('/project-update', [OwnerEmployeeController::class, 'updateProject'])->middleware(['auth:api', 'usertype:owner']);
    //delete project
    Route::post('/project-delete', [OwnerEmployeeController::class, 'deleteProject'])->middleware(['auth:api', 'usertype:owner']);

     //assign project
     Route::post('/project-assign-add', [OwnerEmployeeController::class, 'projectAssignAdd'])->middleware(['auth:api', 'usertype:owner']);
     //assign project list
     Route::get('/project-assign-list', [OwnerEmployeeController::class, 'projectAssignList'])->middleware(['auth:api', 'usertype:owner']);
     //update assign project
     Route::post('/project-assign-update', [OwnerEmployeeController::class, 'projectAssignUpdate'])->middleware(['auth:api', 'usertype:owner']);
     //delete assign project
     Route::post('/project-assign-delete', [OwnerEmployeeController::class, 'projectAssignDelete'])->middleware(['auth:api', 'usertype:owner']);
     //All task list show
     Route::get('/task-list-show', [OwnerEmployeeController::class, 'taskList'])->middleware(['auth:api', 'usertype:owner']);



    Route::prefix('employee-profile')->group(function () {
        //edit employee's profile
        Route::get('/show', [EmployeeProfileController::class, 'ownerEmployeesProfileShow'])->middleware(['auth:api', 'usertype:owner']);
        Route::post('/edit', [EmployeeProfileController::class, 'ownerEmployeesProfileUpdate'])->middleware(['auth:api', 'usertype:owner']);
        Route::post('/deactivate', [EmployeeProfileController::class, 'ownerEmployeesProfileDeactivate'])->middleware(['auth:api', 'usertype:owner']);
        Route::post('/delete', [EmployeeProfileController::class, 'ownerDeleteEmployeesProfile'])->middleware(['auth:api', 'usertype:owner']);

    });

});



// Route::middleware('owner')->group(function () {

// });
