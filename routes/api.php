<?php


use App\Http\Controllers\CompanyControllers\AuthController as CompanyControllersAuthController;
use App\Http\Controllers\CompanyControllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::group(['prefix' => 'user'], function () {
//     Route::POST('/signup', [AuthController::class, 'SignUp']);
//     Route::POST('/login', [AuthController::class, 'login']);
// });

Route::group(['prefix' => 'company'], function () {
    Route::post('/login', [CompanyControllersAuthController::class, 'login']);
    Route::post('/sign-up', [CompanyControllersAuthController::class, 'signUp']);
    Route::post('/guard', [CompanyControllersAuthController::class, 'guard']);
    Route::apiResource('/category', CategoryController::class);
});