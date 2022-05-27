<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Order;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\RetailDealer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(CompanyUser $companyUser)
    {
    }

    public function view(CompanyUser $companyUser, Order $order)
    {
    }

    public function create(RetailDealer $retailDealer, $company_id)
    {
        $check = Subscribe::where('company_id', '=', $company_id)
            ->where('retail_dealer_id', '=', $retailDealer->id)
            ->count();

        if ($check > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function isDriver(CompanyUser $companyUser)
    {
        return $companyUser->user_type == Config::get('constants.company.users.driver_type');
    }

    public function update(CompanyUser $companyUser, Order $order)
    {
    }

    public function delete(CompanyUser $companyUser, Order $order)
    {
    }

    public function restore(CompanyUser $companyUser, Order $order)
    {
    }

    public function forceDelete(CompanyUser $companyUser, Order $order)
    {
    }
}