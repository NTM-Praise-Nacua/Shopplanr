<?php

use App\Http\Controllers\ShopPlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::post('/user/login', [UserController::class, 'login'])->name('user.login');

Route::middleware(['auth'])->group(function() {
    Route::get('/list', [ShopPlanController::class, 'index'])->name('list');
    Route::get('/settings', function() {
        return view('auth.settings');
    })->name('settings');
    
    Route::get('/plan-create', [ShopPlanController::class, 'create'])->name('create');
    Route::post('/plan/store', [ShopPlanController::class, 'store'])->name('plan.store');
    
    Route::get('/plan-update/{id}', [ShopPlanController::class, 'edit'])->name('update');
    Route::post('/plan/start', [ShopPlanController::class, 'startPlan'])->name('plan.start');
    Route::put('/plan/{id}', [ShopPlanController::class, 'update'])->name('plan.update');

    Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');
});
