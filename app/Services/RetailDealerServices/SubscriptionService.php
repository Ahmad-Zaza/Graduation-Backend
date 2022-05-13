<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SubscriptionService
{
    use QueryTrait;

    public static function getAllCompanies($name)
    {
        $companies = Company::where('name', $name)
            ->limit(10);
        return (new static)->successMessage($companies, '200');
    }

    public static function sendSubscribeRequest(Request $request)
    {

        if ((new static)->checkNotExistingRequest($request->company_id, $request->retail_dealer_id)) {
            // return response($this->checkNotExistingRequest($request->company_id, $request->retail_dealer_id));
            return (new static)->errorMessage(null, '414', 'request sended before !!');
        }

        $subRequest = SubscribeRequest::create([
            'company_id' => $request->company_id,
            'retail_dealer_id' => $request->retail_dealer_id
        ]);
        $subRequest['status'] = Config::get('constants.retailDealer.subscribeRequest.pending');
        $subRequest['retail-dealer'] = $subRequest->retailDealer()->get();
        return (new static)->successMessage($subRequest, '200');
    }

    public function checkNotExistingRequest($company_id, $ret_dea_id)
    {
        $subRequest = SubscribeRequest::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();
        $subRequest1 = Subscribe::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();

        if ($subRequest || $subRequest1) {
            return true;
        }
        return false;
    }
}