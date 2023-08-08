<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Owner\AuthController;
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
});
