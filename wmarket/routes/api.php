<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EnterpriseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ObservabilityController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\StateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Unprotected routes
 */
Route::post('/login', [UserController::class, 'login']);
Route::post('/login/isAuthorized', [UserController::class, 'isAuthorized'])->middleware('auth:sanctum');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/feedbacks', [FeedbackController::class, 'create']);

Route::post('/users', [UserController::class, 'create']);
Route::post('/users/recovery', [UserController::class, 'recoveryPassword']);
Route::post('/users/changePassword', [UserController::class, 'resetPasswordByToken']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{idCity}', [CategoryController::class, 'getByCity']);

Route::get('/images/{folder}/{filename}', [ImageController::class, 'getImage']);

Route::post('/enterprises', [EnterpriseController::class, 'create']);
Route::post('/enterprises/search', [EnterpriseController::class, 'search']);

Route::get('/enterprises/{id}', [EnterpriseController::class, 'show']);

Route::get('/states/{id}', [StateController::class, 'getByCountry']);

/**
 * Protected routes
 */
Route::group(['prefix' => 'v1',  'middleware' => ['auth:sanctum', 'userIsAllowed']], function() {
    // Users api routes
    Route::apiResource('/users', UserController::class);
    Route::post('/users/search', [UserController::class, 'search']);
    // Groups api routes
    Route::apiResource('/groups', GroupController::class);
    Route::post('/groups/search', [GroupController::class, 'search']);
    // Roles api routes
    Route::apiResource('/roles', RoleController::class);
    Route::post('/roles/groups', [RoleController::class, 'addRoleToGroup']);
    Route::delete('/roles/groups/{id}', [RoleController::class, 'removeRoleToGroup']);
    Route::post('/roles/search', [RoleController::class, 'search']);
    // Route api categories
    Route::apiResource('/categories', CategoryController::class);
    // Route api enterprises
    Route::apiResource('/enterprises', EnterpriseController::class);
    // Route api states
    Route::apiResource('/states', StateController::class);
    // Route api countries
    Route::apiResource('/countries', CountryController::class);
    // Route api cities
    Route::apiResource('/cities', CityController::class);
    // Route api feedbacks
    Route::apiResource('/feedbacks', FeedbackController::class);
    // Route api observability
    Route::get('/observability/metrics', [ObservabilityController::class, 'getMetrics']);
    Route::post('/observability/logs', [ObservabilityController::class, 'getLogs']);
    // Route api ratings
    Route::apiResource('/ratings', RatingController::class);
});
