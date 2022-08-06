<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\OrderReview;
use App\Models\RetailDealersModel\RetailDealer;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function getOrderReview($order_id)
    {
        $review = OrderReview::with('order')
            ->where('order_id', $order_id);
        $review["retail_dealer"] = RetailDealer::find($review->retail_dealer_id);
        return $this->successMessage($review, '200');
    }

    public function getMyCompanyReviews()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        $per_page = request()->per_page ?? 10;

        $reviews = OrderReview::with('order')
            ->where('company_id', $company_id)
            ->paginate($per_page);

        foreach($reviews as $review){
            $review["retail_dealer"] = RetailDealer::find($review->retail_dealer_id);
        }

        return $this->successMessage($reviews, '200');
    }
}
