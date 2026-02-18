<?php

use App\Http\Controllers\api\ItemController;
use App\Http\Controllers\api\ShopPlanController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('users', UserController::class);
Route::post('/users/login', [UserController::class, 'login']);
Route::apiResource('shop_plans', ShopPlanController::class);
Route::put('/shop_plans/update-status/{id}', [ShopPlanController::class, 'updateStatus']);
Route::put('/shop_plans/overdue/{id}', [ShopPlanController::class, 'updateOverdue']);
Route::put('/shop_plans/checkUp/{id}', [ShopPlanController::class, 'checkUpdate']);
Route::put('/shop_plans/start/{id}', [ShopPlanController::class, 'startPlan']);
Route::get('/shop_plans/by-user/{id}', [ShopPlanController::class, 'getShopPlansByUser']);
Route::get('/shop_plans/items/{id}', [ShopPlanController::class, 'getItemsByPlan']);
Route::post('/items', [ItemController::class, 'store']);

Route::get('/test', [UserController::class, 'test']);
// Route::get('/test', function() {
//     return response()->json([
//         "success" => true,
//         "message" => "test from api",
//         "data" => [
//             'message' => 'test from api'
//         ]
//     ]);
// });