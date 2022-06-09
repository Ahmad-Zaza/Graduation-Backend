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
use Illuminate\Support\Facades\Gate;
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

    public function viewRetailDealerOrders($retail_dealer_id)
    {
        return $this->orderService->viewRetailDealerOrders($retail_dealer_id);
    }

    public function goTolive(Request $request)
    {

        if (!Gate::allows('isDriver', [Order::class])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array',
            'order_ids.*.order_id' => [
                'required',
                'numeric',
                Rule::exists(Order::class, 'id')->where(function ($query) {
                    return $query->where('status', Config::get('constants.company.order.accepted'))
                    ->orWhere(
                        'status', Config::get('constants.company.order.delivering'));

                })
            ]
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, $validator->errors());
        }

        foreach ($request->order_ids as $order_id) {
            $order = Order::where('id', $order_id)->get()[0];
            // return response($order);
            $order->update(['status' => Config::get('constants.company.order.delivering')]);
        }

        return response()->json([
            'msg' => 'orders status has been updated successfully',
            'code' => '200'
        ]);
    }

    public function completeOrder(Request $request)
    {
        if (!Gate::allows('isDriver', [Order::class])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'order_id' =>  [
                'required',
                'numeric',
                Rule::exists(Order::class, 'id')->where(function ($query) {
                    return $query->where('status', Config::get('constants.company.order.delivering'));
                })
            ]

        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $order = Order::find($request->order_id)->first();
        $order->update(['status' => Config::get('constants.company.order.completed')]);
        return response()->json([
            'msg' => 'order has been updated successfully',
            'code' => '200'
        ]);
    }
}