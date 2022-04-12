<?php


use App\Http\Controllers\CompanyControllers\AuthController as CompanyControllersAuthController;
use App\Http\Controllers\CompanyControllers\CategoryController;
use App\Http\Controllers\CompanyControllers\MainCompanyController;
use App\Http\Controllers\CompanyControllers\ProductController;
use App\Http\Controllers\CompanyControllers\TruckController;
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
    // product
    Route::post('/new-product', [ProductController::class, 'addNewProduct'])->name('company.new-product');
    Route::put('/update-product/{product_id}', [ProductController::class, 'updateProduct'])->name('company.update-product');
    Route::get('/products/{company_id}', [ProductController::class, 'viewAllProducts'])->name('company.products.index');
    Route::get('/product/{product_id}', [ProductController::class, 'viewProduct'])->name('company.product.show');
    //product types
    Route::post('/new-product_type', [ProductController::class, 'addNewProductType'])->name('company.new-product_type');
    Route::put('/update-product_type/{product_type_id}', [ProductController::class, 'updateProductType'])->name('company.update-product_type');
    Route::get('/product_types/{company_id}', [ProductController::class, 'viewAllProductTypes'])->name('company.product_types.index');
    Route::get('/product_type/{product_type_id}', [ProductController::class, 'viewProductType'])->name('company.product_type.show');
    // truck
    Route::post('/new-truck', [TruckController::class, 'addNewTruck'])->name('company.new-truck');
    Route::put('/update-truck/{truck_id}', [TruckController::class, 'updateTruck'])->name('company.update-truck');
    Route::get('/truck/{truck_id}', [TruckController::class, 'viewTruck'])->name('company.truck.show');
    Route::get('/trucks/{company_id}', [TruckController::class, 'viewAllTrucks'])->name('company.trucks.index');
});