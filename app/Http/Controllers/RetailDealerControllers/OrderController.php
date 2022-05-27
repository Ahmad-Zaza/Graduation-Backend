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

    public function zcompaniesQuerySearch()
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
        // gat
        if (!Gate::forUser(Auth::guard('retail-dealer-api')->user())->allows('create', [Order::class, $request->company_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'retail_dealer_id' => 'required|exists:retail_dealers,id',
            'company_id' => 'required|exists:companies,id',
            'total_price' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_count' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        return $this->orderService->makeOrder($request);
    }

    public function viewAllOrders()
    {
        return $this->orderService->viewAllOrders();
    }

    public function viewRetailDealerOrderDetails($order_id)
    {
        return $this->orderService->viewOrderDetails($order_id);
    }

    public function viewMyOrdersInCompany($company_id)
    {
        // get
        return $this->orderService->viewMyOrdersInCompany($company_id);
    }
}