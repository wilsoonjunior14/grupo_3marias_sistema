<?php

use App\Http\Controllers\AccountantController;
use App\Http\Controllers\BillsPayController;
use App\Http\Controllers\BillsReceiveController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CategoryServiceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\EnterpriseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ObservabilityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\EnterpriseBranchController;
use App\Http\Controllers\EnterpriseFileController;
use App\Http\Controllers\EnterpriseOwnerController;
use App\Http\Controllers\EnterprisePartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ServiceOrderController;

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

Route::post('/users', [UserController::class, 'create']);
Route::post('/users/recovery', [UserController::class, 'recoveryPassword']);
Route::post('/users/changePassword', [UserController::class, 'resetPasswordByToken']);

Route::post('/enterprises', [EnterpriseController::class, 'create']);
Route::post('/enterprises/search', [EnterpriseController::class, 'search']);

Route::get('/enterprises/{id}', [EnterpriseController::class, 'show']);

Route::get('/states/{id}', [StateController::class, 'getByCountry']);

/**
 * Protected routes
 */
Route::group(['prefix' => 'v1',  'middleware' => ['auth:sanctum', 'userIsAllowed']], function() {

    // Stocks api routes
    Route::apiResource('/stocks', StockController::class);
    Route::post('/stocks/share', [StockController::class, 'share']);

    // Clients api routes
    Route::apiResource('/clients', ClientController::class);
    Route::post('/clients/docs', [ClientController::class, 'saveDocuments']);
    Route::delete('/clients/deleteDocs/{id}', [ClientController::class, 'deleteDocument']);
    Route::post('/clients/birthdates', [ClientController::class, 'getBirthdates']);

    // Accountants api routes
    Route::apiResource('/accountants', AccountantController::class);

    // EnterprisePartners api routes
    Route::apiResource('/enterprisePartners', EnterprisePartnerController::class);

    // EnterpriseOwners api routes
    Route::apiResource('/enterpriseOwners', EnterpriseOwnerController::class);

    // EnterpriseBranches api routes
    Route::apiResource('/enterpriseBranches', EnterpriseBranchController::class);

    // EnterpriseFiles api routes
    Route::apiResource('/enterpriseFiles', EnterpriseFileController::class);

    // Products api routes
    Route::apiResource('/products', ProductController::class);

    // Projects api routes
    Route::apiResource('/projects', ProjectController::class);

    // Proposals api routes
    Route::apiResource('/proposals', ProposalController::class);
    Route::post('/proposals/approve/{id}', [ProposalController::class, 'approve']);
    Route::post('/proposals/reject/{id}', [ProposalController::class, 'reject']);

    // CategoryProducts api routes
    Route::apiResource('/categoryProducts', CategoryProductController::class);

    // CategoryServices api routes
    Route::apiResource('/categoryServices', CategoryServiceController::class);

    // Services api routes
    Route::apiResource('/services', ServiceController::class);

    // contracts api routes
    Route::apiResource('/contracts', ContractController::class);

    // bills to pay api routes
    Route::apiResource('/billsPay', BillsPayController::class);

    // bills to pay api routes
    Route::apiResource('/engineers', EngineerController::class);

    // billsReceive api routes
    //Route::apiResource('/billsReceive', BillsReceiveController::class);
    Route::get('/billsReceive', [BillsReceiveController::class, 'index']);
    Route::get('/billsReceive/{id}', [BillsReceiveController::class, 'show']);
    Route::put('/billsReceive/{id}', [BillsReceiveController::class, 'update']);
    Route::get('/billsReceive/get/inProgress', [BillsReceiveController::class, 'inprogress']);

    // Partners api routes
    Route::apiResource('/partners', PartnerController::class);
    
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
    // Route api purchaseOrders
    Route::apiResource('/purchaseOrders', PurchaseOrderController::class);
    Route::post('/purchaseOrders/approve/{id}', [PurchaseOrderController::class, 'approve']);
    Route::post('/purchaseOrders/reject/{id}', [PurchaseOrderController::class, 'reject']);
    // Route api serviceOrders
    Route::apiResource('/serviceOrders', ServiceOrderController::class);
    // Route api enterprises
    Route::apiResource('/enterprises', EnterpriseController::class);
    // Route api states
    Route::apiResource('/states', StateController::class);
    // Route api countries
    Route::apiResource('/countries', CountryController::class);
    // Route api cities
    Route::apiResource('/cities', CityController::class);
    // Route api observability
    Route::get('/observability/metrics', [ObservabilityController::class, 'getMetrics']);
    Route::post('/observability/logs', [ObservabilityController::class, 'getLogs']);
});
