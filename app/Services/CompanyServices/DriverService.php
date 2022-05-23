<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Order;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Class DriverService.
 */
class DriverService
{
    use QueryTrait;

    public static function viewAllDrivers($company_id)
    {
        $per_page = request()->per_page ?? 10;

        $drivers = CompanyUser::where('company_id', $company_id)
            ->where('user_type', Config::get('constants.company.users.driver_type'))
            ->paginate($per_page);

        return (new static)->successMessage($drivers, '200');
    }

    public static function deleteDriver($driver_id)
    {
        $driver = CompanyUser::find($driver_id);

        $driver->delete();

        return response()->json('Driver has been deleted');
    }

    public static function getDriverOrders()
    {
        $per_page = request()->per_page ?? 10;
        $driver_id = Auth::guard('company-api')->user()->id;
        $orders = Order::with('retailDealer')
            ->where('company_user_id', '=', $driver_id)
            ->where('status', '=', Config::get('constants.company.order.accepted'))
            ->orWhere('status', '=', Config::get('constants.company.order.delivering'))
            ->paginate($per_page);

        return (new static)->successMessage($orders, '200');
    }

    public static function getCompletedDriverOrders()
    {
        $per_page = request()->per_page ?? 10;
        $driver_id = Auth::guard('company-api')->user()->id;
        $orders = Order::with('retailDealer')
            ->where('company_user_id', '=', $driver_id)
            ->where('status', '=', Config::get('constants.company.order.completed'))
            ->paginate($per_page);

        return (new static)->successMessage($orders, '200');
    }

    public static function driverQuerySearch()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        $searchText = request()->searchText;
        $drivers = CompanyUser::with('company')
            ->where('company_id', $company_id)
            ->where('user_type', '=', Config::get('constants.company.users.driver_type'))
            ->where(function ($query) use ($searchText) {
                $query->where('first_name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('username', 'LIKE', '%' . $searchText . '%');
            })

            ->limit(5)
            ->get();
        return (new static)->successMessage($drivers, '200');
    }
}