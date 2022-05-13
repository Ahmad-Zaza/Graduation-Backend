<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\RetailDealersModel\RetailDealer;
use App\Models\RetailDealersModel\SubscribeRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealerSubscriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(RetailDealer $retailDealer)
    {
        //
    }

    public function send(RetailDealer $retailDealer, $retail_dealer_id)
    {
        return $retail_dealer_id->id == $retail_dealer_id;
    }

    public function view(RetailDealer $retailDealer, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function create(RetailDealer $retailDealer)
    {
        //
    }

    public function update(RetailDealer $retailDealer, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function delete(RetailDealer $retailDealer, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function restore(RetailDealer $retailDealer, SubscribeRequest $subscribeRequest)
    {
        //
    }

    public function forceDelete(RetailDealer $retailDealer, SubscribeRequest $subscribeRequest)
    {
        //
    }
}