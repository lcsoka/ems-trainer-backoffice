<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TrainingController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [UserController::class, 'me']);

    Route::get('/training/all', [TrainingController::class, 'trainings']);
    Route::post('/training/create', [TrainingController::class, 'create']);

    Route::get('/auth/logout', [AuthController::class, 'logout']);
});
