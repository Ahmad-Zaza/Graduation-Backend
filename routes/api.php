<?php


use App\Http\Controllers\CompanyControllers\AuthController as CompanyControllersAuthController;
use App\Http\Controllers\CompanyControllers\CategoryController;
use App\Http\Controllers\CompanyControllers\DriverController;
use App\Http\Controllers\CompanyControllers\MainCompanyController;
use App\Http\Controllers\CompanyControllers\OrderController as CompanyControllersOrderController;
use App\Http\Controllers\CompanyControllers\ProductController;
use App\Http\Controllers\CompanyControllers\SubscriptionController as CompanyControllersSubscriptionController;
use App\Http\Controllers\CompanyControllers\TruckController;
use App\Http\Controllers\RetailDealerControllers\AuthController;
use App\Http\Controllers\RetailDealerControllers\CategoryContoller;
use App\Http\Controllers\RetailDealerControllers\MainController;
use App\Http\Controllers\RetailDealerControllers\OrderController;
use App\Http\Controllers\RetailDealerControllers\SubscriptionController;
use App\Models\CompanyModels\Category;
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
    Route::get('/test', function () {
        return ['test', 'is', 'done', 'successfully'];
    });
    // admin info
    Route::get('/admin-basic-info', [MainCompanyController::class, 'getBasicInfo'])->name('company.basic_info.show');
    // category
    Route::post('/category', [CategoryController::class, 'store'])->name('company.category.store');
    Route::get('/company-categories/{company_id}', [CategoryController::class, 'index'])->name('company.categories.index');
    Route::get('/category/{category_id}', [CategoryController::class, 'show'])->name('company.category.show');
    Route::put('/category/{category_id}', [CategoryController::class, 'update'])->name('company.category.update');
    Route::delete('/category/{category_id}', [CategoryController::class, 'destroy'])->name('company.category.destroy');
    Route::get('category-search', [CategoryController::class, 'categoryQuerySearch'])->name('company.category-QuerySearch');
    // company users
    Route::post('/new-admin', [MainCompanyController::class, 'addNewAdmin'])->name('company.new-admin');
    Route::post('/new-driver', [MainCompanyController::class, 'addNewDriver'])->name('company.new-driver');
    Route::get('/company-users/{company_id}', [MainCompanyController::class, 'getCompanyUsers'])->name('company.all-users');

    // product
    Route::post('/new-product', [ProductController::class, 'addNewProduct'])->name('company.new-product');
    Route::put('/update-product/{product_id}', [ProductController::class, 'updateProduct'])->name('company.update-product');
    Route::get('/products/{company_id}', [ProductController::class, 'viewAllProducts'])->name('company.products.index');
    Route::get('/product/{product_id}', [ProductController::class, 'viewProduct'])->name('company.product.show');
    Route::get('/product-search', [ProductController::class, 'ProductQuerySearch'])->name('company.products.QuerySearch');
    Route::get('/products-category/{category_id}', [ProductController::class, 'viewAllProductByCategoryId'])->name('company.category_products.index');
    // product types
    Route::post('/new-product_type', [ProductController::class, 'addNewProductType'])->name('company.new-product_type');
    Route::put('/update-product_type/{product_type_id}', [ProductController::class, 'updateProductType'])->name('company.update-product_type');
    Route::get('/product_types/{company_id}', [ProductController::class, 'viewAllProductTypes'])->name('company.product_types.index');
    Route::get('/product_type/{product_type_id}', [ProductController::class, 'viewProductType'])->name('company.product_type.show');
    Route::get('/product-types-search', [ProductController::class, 'productTypeQuerySearch'])->name('company.product_types.QuerySearch');
    // truck
    Route::post('/new-truck', [TruckController::class, 'addNewTruck'])->name('company.new-truck');
    Route::put('/update-truck/{truck_id}', [TruckController::class, 'updateTruck'])->name('company.update-truck');
    Route::get('/truck/{truck_id}', [TruckController::class, 'viewTruck'])->name('company.truck.show');
    Route::get('/trucks/{company_id}', [TruckController::class, 'viewAllTrucks'])->name('company.trucks.index');
    Route::get('/truck-search', [TruckController::class, 'truckQuerySearch'])->name('company.truck.search');
    // subscribes
    Route::get('/subscribes-requests', [CompanyControllersSubscriptionController::class, 'getAllRequests'])->name('company.subscribes_requests.index');
    Route::delete('/update-request/{req_id}', [CompanyControllersSubscriptionController::class, 'editRequest'])->name('company.subscribe_request.edit');
    Route::get('/retail-dealers', [CompanyControllersSubscriptionController::class, 'viewAllRetailDealers'])->name('company.retail_dealers.index');
    // drivers
    Route::get('/drivers', [DriverController::class, 'viewAllDrivers'])->name('company.drivers.index');
    Route::get('/driver-search', [DriverController::class, 'driverQuerySearch'])->name('company.driver.search');
    Route::delete('/delete-driver/{driver_id}', [DriverController::class, 'deleteDriver'])->name('company.driver.delete');
    // drivers orders
    Route::get('/assigned-driver-orders', [DriverController::class, 'getDriverOrders'])->name('company.driver_assigned_orders.index');
    Route::get('/completed-driver-orders', [DriverController::class, 'getCompletedDriverOrders'])->name('company.driver_completed_orders.index');

    // orders
    Route::get('/orders', [CompanyControllersOrderController::class, 'viewAllOrders'])->name('company.orders.index');
    Route::get('/retail-dealer-orders/{retail_dealer_id}', [CompanyControllersOrderController::class, 'viewRetailDealerOrders'])->name('company.retail_dealer_orders.index');
    Route::get('/order-details/{order_id}', [CompanyControllersOrderController::class, 'viewOrderDetails'])->name('company.order_details.show');
    // just admin of company
    Route::put('/assign-order', [CompanyControllersOrderController::class, 'assignOrderToDriver'])->name('company.order.assign');
    Route::put('/cancel-order', [CompanyControllersOrderController::class, 'cancelOrder'])->name('company.order.cancel');
    // just driver
    Route::put('/go-orders-to-live', [CompanyControllersOrderController::class, 'goTolive'])->name('company.orders.go_live');
    Route::put('/complete-order', [CompanyControllersOrderController::class, 'completeOrder'])->name('company.order.complete');
    //
});

Route::group(['prefix' => 'retail-dealer'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/sign-up', [AuthController::class, 'signUp']);
    Route::post('/send-subscribe-request', [SubscriptionController::class, 'sendSubscribeRequest'])->name('retaild_dealer.send_subscription_request');
    Route::get('/main-info', [MainController::class, 'viewMainInfo'])->name('retail_dealer.main-info');
    // orders
    Route::get('/my-companies', [OrderController::class, 'retailDealerCompanies'])->name('retail_dealer.my-companies.index');
    Route::get('/company-search', [OrderController::class, 'companiesQuerySearch'])->name('retail_dealer.company.search');
    Route::get('/company-products/{company_id}', [OrderController::class, 'companyProducts'])->name('retail_dealer.company_products.index');
    Route::get('/company-categories/{company_id}', [CategoryContoller::class, 'viewCompanyCategories'])->name('retail_dealer.company_categories.index');
    Route::get('/category-products/{category_id}', [CategoryContoller::class, 'viewProductsByCategoryId'])->name('retail_dealer.company_category_products.index');
    Route::post('/send-order', [OrderController::class, 'makeOrder'])->name('retail_dealer.order.send');
    Route::get('/my-orders', [OrderController::class, 'viewAllOrders'])->name('retail_dealer.my_orders.index');
    Route::get('/my-orders/{company_id}', [OrderController::class, 'viewMyOrdersInCompany'])->name('retail_dealer.my_orders_company.index');
    Route::get('/order-details/{order_id}', [OrderController::class, 'viewRetailDealerOrderDetails'])->name('retail_dealer.order_details.show');
    // companies
    Route::get('/unsubscribed-companies', [SubscriptionController::class, 'unsubscribedCompanies'])->name('retail_dealer.unsubscribed_companies.index');
});

Route::post('add-category', function (Request $request) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');

    $cat = Category::create([
        'name' => $request->name,
        'description' => $request->description,
        'company_id' => 1
    ]);

    return response()->json([
        'result' => $cat,
        200
    ]);
});