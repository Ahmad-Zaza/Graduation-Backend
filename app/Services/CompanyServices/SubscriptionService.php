<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\RetailDealer;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class SubscriptionService.
 */
class SubscriptionService
{
    use QueryTrait;

    public static function getAllRequests()
    {
        $per_page = request()->per_page ?? 10;

        $requests = SubscribeRequest::where('company_id', Auth::guard('company-api')->user()->company_id)
            ->where('status', Config::get('constants.retailDealer.subscribeRequest.pending'))
            ->paginate($per_page);
        foreach ($requests as $my_request) {
            $my_request['retail_dealer'] = RetailDealer::find($my_request->retail_dealer_id);
            $my_request['company'] = Company::find($my_request->company_id);
        }
        return (new static)->successMessage($requests, '200');
    }

    public static function editRequest(Request $request, $request_id)
    {
        $subRequest = SubscribeRequest::find($request_id);

        $subRequest->delete();

        if ($request->status == Config::get('constants.retailDealer.subscribeRequest.accepted')) {
            $subscribe = Subscribe::create([
                'company_id' => $request->company_id,
                'retail_dealer_id' => $request->retail_dealer_id,
            ]);
            return response()->json([
                'msg' => 'accepted done',
                'code' => '200'
            ]);
        } else if ($request->status == Config::get('constants.retailDealer.subscribeRequest.rejected')) {
            return response()->json([
                'msg' => 'rejected done',
                'code' => '200'
            ]);
        }
    }

    public static function viewAllRetailDealers($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $retail_dealers = DB::table('subscribes')
            ->join('retail_dealers', 'retail_dealers.id', 'subscribes.retail_dealer_id')
            ->where('subscribes.company_id', '=', $company_id)
            ->select(
                'retail_dealers.id',
                'retail_dealers.first_name',
                'retail_dealers.last_name',
                'retail_dealers.username',
                'retail_dealers.phone_number',
                'retail_dealers.email',
                'retail_dealers.account_status',
                'retail_dealers.longitude',
                'retail_dealers.latitude',
                'retail_dealers.created_at',
                'retail_dealers.updated_at'
            )
            ->groupBy(
                'retail_dealers.id',
                'retail_dealers.first_name',
                'retail_dealers.last_name',
                'retail_dealers.username',
                'retail_dealers.phone_number',
                'retail_dealers.email',
                'retail_dealers.account_status',
                'retail_dealers.longitude',
                'retail_dealers.latitude',
                'retail_dealers.created_at',
                'retail_dealers.updated_at'
            )
            ->paginate($per_page);

        return (new static)->successMessage($retail_dealers, '200');
    }
}