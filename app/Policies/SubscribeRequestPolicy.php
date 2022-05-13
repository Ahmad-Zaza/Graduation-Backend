<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\RetailDealersModel\RetailDealer;
use App\Models\RetailDealersModel\SubscribeRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscribeRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(CompanyUser $companyUser, $company_id, $ret_deal_id1, $ret_deal_id2)
    {
        return $companyUser->company_id == $company_id && $companyUser->user_type == 1 && $ret_deal_id1 == $ret_deal_id2;
    }

    public function send(RetailDealer $retailDealer, $retail_dealer_id)
    {
        return $retailDealer->id == $retail_dealer_id;
    }

    public function view(CompanyUser $companyUser, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function create(CompanyUser $companyUser)
    {
        //
    }

    public function update(CompanyUser $companyUser, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function delete(CompanyUser $companyUser, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function restore(CompanyUser $companyUser, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function forceDelete(CompanyUser $companyUser, SubscribeRequest $subscribeRequest)
    {
        //
    }
}