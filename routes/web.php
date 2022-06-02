<?php

use App\Events\LiveOrderEvent;
use App\Events\TestEvent;
use App\Models\CompanyModels\Order;
use Illuminate\Support\Facades\Auth;
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
    $data = new Order();
    $data->id  = 1;
    event(new TestEvent(null));
    event(new LiveOrderEvent($data));
    $user = Auth::guard('company-api')->check();
    // Debugbar::info("debugerrr", [$user]);
    return view('welcome', ['user' => $user]);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');