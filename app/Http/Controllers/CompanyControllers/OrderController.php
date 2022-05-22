<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Order;
use App\Services\CompanyServices\OrderService;
use App\Traits\QueryTrait;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use QueryTrait;
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->middleware('assign.guard:company-api');
        $this->orderService = $orderService;
    }

    public function viewAllOrders()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        return $this->orderService->viewAllOrders($company_id);
    }

    public function viewOrderDetails($order_id)
    {
        return $this->orderService->viewOrderDetails($order_id);
    }


    public function assignOrderToDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|numeric',
            'company_user_id' => [ //driver
                'required',
                Rule::exists('company_users', 'id'),
                // ->where('user_type', Config::get('constants.company.users.driver_type')),
            ]

        ]);



        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        return $this->orderService->assignOrderToDriver($request);
    }

    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        return $this->orderService->cancelOrder($request);
    }
}