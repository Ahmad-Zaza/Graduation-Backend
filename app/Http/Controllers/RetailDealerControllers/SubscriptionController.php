<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Services\RetailDealerServices\SubscriptionService;
use App\Traits\QueryTrait;
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
        $this->middleware('assign.guard:retail-dealer-api');
        $this->subscriptionService = $subscriptionService;
    }

    public function getAllCompanies($name)
    {
        return $this->subscriptionService->getAllCompanies($name);
    }

    public function sendSubscribeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        if (!Gate::forUser(Auth::guard('retail-dealer-api')->user())->allows('send', [SubscribeRequest::class, $request->retail_dealer_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        return $this->subscriptionService->sendSubscribeRequest($request);
    }

    public function checkNotExistingRequest($company_id, $ret_dea_id)
    {
        $subRequest = SubscribeRequest::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();
        $subRequest1 =
            Subscribe::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();
        if ($subRequest || $subRequest1) {
            return true;
        }
        return false;
    }

    public function unsubscribedCompanies()
    {
        $per_page = request()->per_page ?? 10;
        $ret_deal_id = Auth::guard('retail-dealer-api')->user()->id;
        $companies = DB::table('companies')
            ->select(
                'companies.*'
            )
            ->whereNotIn('companies.id', function ($query) use ($ret_deal_id) {
                $query->select('subscribes.company_id')->from('subscribes')
                    ->where('subscribes.retail_dealer_id', $ret_deal_id);
            })
            ->paginate($per_page);

        return $this->successMessage($companies, '200');
    }
}