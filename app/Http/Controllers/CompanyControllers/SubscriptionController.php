<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\RetailDealer;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Services\CompanyServices\SubscriptionService;
use App\Traits\QueryTrait;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use QueryTrait;
    protected $subscriptionService;
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->middleware('assign.guard:company-api');
        $this->subscriptionService = $subscriptionService;
    }

    public function getAllRequests()
    {
        return $this->subscriptionService->getAllRequests();
    }

    public function editRequest(Request $request, $request_id)
    {
        $subRequest = SubscribeRequest::find($request_id);
        // return response($subRequest);
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                'numeric',
                Rule::in([1, 3])
            ],
            'company_id' => 'required|exists:companies,id',
            'retail_dealer_id' => 'required|exists:retail_dealers,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        if (!Gate::allows('viewAny', [SubscribeRequest::class, $request->company_id, $subRequest->retail_dealer_id, $request->retail_dealer_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
            // abort(403);
        }

        return $this->subscriptionService->editRequest($request, $request_id);
    }

    public function viewAllRetailDealers()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        if (!Gate::forUser(Auth::guard('company-api')->user())->allows('viewAllRetailDealers', [Subscribe::class, $company_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        return $this->subscriptionService->viewAllRetailDealers($company_id);
    }
}