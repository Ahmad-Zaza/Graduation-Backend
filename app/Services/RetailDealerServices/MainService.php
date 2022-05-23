<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Order;
use App\Models\CompanyModels\Subscribe;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Class MainService.
 */
class MainService
{
    use QueryTrait;

    public static function viewMainInfo()
    {
        $ret_dealer = Auth::guard('retail-dealer-api')->user();
        $companies = Subscribe::where('retail_dealer_id', '=', $ret_dealer->id)->count();
        $orders = Order::where('retail_dealer_id', '=', $ret_dealer->id)->count();
        $liveOrders = Order::where('retail_dealer_id', '=', $ret_dealer->id)
            ->where('status', '=', Config::get('constants.company.order.delivering'))
            ->count();

        return response()->json([
            'companies_count' => $companies,
            'orders_count' => $orders,
            'live_orders_count' => $liveOrders
        ], '200');
    }
}