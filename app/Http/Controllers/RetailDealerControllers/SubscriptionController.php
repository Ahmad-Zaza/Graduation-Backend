<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:retail-dealer-api');
    }

    public function getAllCompanies($name)
    {
        $companies = Company::where('name', $name)
            ->limit(10);
        return $this->successMessage($companies, '200');
    }

    public function sendSubscribeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'retail_dealer_id' => 'required|exists:retail_dealers,id'
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        if ($this->checkNotExistingRequest($request->company_id, $request->retail_dealer_id)) {
            // return response($this->checkNotExistingRequest($request->company_id, $request->retail_dealer_id));
            return $this->errorMessage(null, '414', 'request sended before !!');
        }

        $subRequest = SubscribeRequest::create($request->all());
        $subRequest['status'] = Config::get('constants.retailDealer.subscribeRequest.pending');
        return $this->successMessage($subRequest, '200');
    }

    public function checkNotExistingRequest($company_id, $ret_dea_id)
    {
        $subRequest = SubscribeRequest::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();

        if ($subRequest) {
            return true;
        }
        return false;
    }
}