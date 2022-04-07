<?php


use App\Http\Controllers\CompanyControllers\AuthController as CompanyControllersAuthController;
use App\Http\Controllers\CompanyControllers\CategoryController;
use App\Http\Controllers\CompanyControllers\MainCompanyController;
use App\Http\Controllers\CompanyControllers\ProductController;
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
    // category
    Route::post('/category', [CategoryController::class, 'store'])->name('company.category.store');
    Route::get('/category/{company_id}', [CategoryController::class, 'index'])->name('company.categories.index');
    Route::get('/category/{category_id}', [CategoryController::class, 'show'])->name('company.category.show');
    Route::put('/category/{category_id}', [CategoryController::class, 'update'])->name('company.category.update');
    Route::delete('/category/{category_id', [CategoryController::class, 'destroy'])->name('company.category.destroy');
    // company users
    Route::post('/new-admin', [MainCompanyController::class, 'addNewAdmin'])->name('company.new-admin');
    Route::post('/new-driver', [MainCompanyController::class, 'addNewDriver'])->name('company.new-driver');
    Route::get('/company-users/{company_id}', [MainCompanyController::class, 'getCompanyUsers'])->name('company.all-users');
    // product and product types
    Route::post('/new-product', [ProductController::class, 'addNewProduct'])->name('company.new-product');
});