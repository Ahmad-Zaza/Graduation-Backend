<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\SubscribeRequest;

use App\Traits\QueryTrait;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use QueryTrait;

    public function __construct()
    {
        $this->middleware('assign.guard:company-api');
    }

    public function getAllRequests()
    {
        $per_page = request()->per_page ?? 10;

        $requests = SubscribeRequest::where('company_id', Auth::guard('company-api')->user()->company_id)
            ->where('status', Config::get('constants.retailDealer.subscribeRequest.pending'))
            ->paginate($per_page);
        return $this->successMessage($requests, '200');
    }

    public function editRequest(Request $request, $request_id)
    {
        $subRequest = SubscribeRequest::find($request_id);
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
}