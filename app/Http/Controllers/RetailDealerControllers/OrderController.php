<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Order;
use App\Models\CompanyModels\OrderDetail;
use App\Models\CompanyModels\Product;
use App\Services\RetailDealerServices\OrderService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use QueryTrait;
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->middleware('assign.guard:retail-dealer-api');
        $this->orderService = $orderService;
    }

    public function retailDealerCompanies()
    {
        return $this->orderService->retailDealerCompanies();
    }

    public function companiesQuerySearch()
    {
        return $this->orderService->companiesQuerySearch();
    }

    public function companyProducts($company_id)
    {
        // if (!Gate::forUser(Auth::guard('retail-dealer-api')->user())->allows('viewProducts', [Product::class, $company_id])) {
        //     return $this->errorMessage(null, '403', 'This action is unauthorized');
        // }
        return $this->orderService->companyProducts($company_id);
    }

    public function makeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'retail_dealer_id' => 'required|exists:retail_dealers,id',
            'company_id' => 'required|exists:companies,id',
            'total_price' => 'required|numeric',
            'products' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }
        // return response($request->products);
        $order = Order::create([
            'retail_dealer_id' => $request->retail_dealer_id,
            'company_id' => $request->company_id,
            'total_price' => $request->total_price
        ]);

        foreach ($request->products as $product) {
            // return response($product['product_id']);
            $order_detail = new OrderDetail();
            $order_detail->order_id = $order->id;
            $order_detail->product_id = $product['product_id'];
            $order_detail->count = $product['product_count'];
            $order_detail->save();
        }

        $order['details'] = $order->orderDetails()->get();

        foreach ($order->details as $order_detail) {
            $order_detail['product'] = Product::find($order_detail->product_id);
        }

        return $this->successMessage($order, '200');
    }
}