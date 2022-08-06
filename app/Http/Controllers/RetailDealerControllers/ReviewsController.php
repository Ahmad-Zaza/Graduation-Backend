<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Order;
use App\Models\OrderReview;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:retail-dealer-api');
    }

    public function makeOrderReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'retail_dealer_id' => 'required|integer',
            'rating' => 'integer'
        ]);

        $order = Order::find($request->order_id);


        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $review = OrderReview::make([
            'order_id' => $request->order_id,
            'retail_dealer_id' => $request->retail_dealer_id,
            'rating' => $request->rating,
            'company_id' => $order->company_id
        ]);

        return $this->successMessage($review, '200');
    }

    public function getMyReviews()
    {
        $retail_dealer_id = Auth::guard('retail-dealer-api')->user()->id;
        $per_page = request()->per_page ?? 10;
        $myReviews = OrderReview::with('order')
            ->where('retail_dealer_id', $retail_dealer_id)
            ->paginate($per_page);

        return $this->successMessage($myReviews, '200');
    }
}
