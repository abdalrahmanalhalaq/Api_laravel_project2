<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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

Route::apiResource('news', NewsController::class);
// ->middleware('auth:admins-api')
Route::get('restore/{restore}', [NewsController::class , 'restore']);
Route::delete('forceDelete/{forceDelete}', [NewsController::class , 'forceDelete']);
Route::get('trush/', [NewsController::class , 'trasheddata']);

Route::prefix('auth')->group(function(){
    Route::post('register' , [AuthController::class , 'register']);
    Route::post('login' , [AuthController::class , 'login']);
    Route::post('forgetPassword' , [AuthController::class , 'forgetPassword'])->middleware('throttle:3,1');
    Route::post('resetPassword' , [AuthController::class , 'resetPassword'])->middleware('throttle:3,1');

});

Route::middleware('auth:admins-api')->group(function(){
        Route::apiResource('roles' , RoleController::class);
        Route::apiResource('permission' , PermissionController::class);
        Route::put('role/{role}/permission/{permission}', [ RoleController::class , 'UpdateRolesPermission']);

});

Route::prefix('auth')->middleware('auth:admins-api')->group(function(){
    Route::get('logout' , [AuthController::class, 'logout']);
    Route::put('changePassword' , [AuthController::class, 'changePassword']);
});

