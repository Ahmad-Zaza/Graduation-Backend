<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\Order;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrderService.
 */
class OrderService
{
    use QueryTrait;
    public static function viewAllOrders($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $status = request()->status ?? '';

        if ($status) {
            $orders = Order::with('retailDealer')
                ->withCount('orderDetails')
                ->with('companyUser')
                ->where('company_id', $company_id)
                ->where('status', '=', $status)
                ->orderby('created_at', 'ASC')
                ->paginate($per_page);
        } else {
            $orders = Order::with('retailDealer')
                ->withCount('orderDetails')
                ->where('company_id', $company_id)
                ->with('companyUser')
                ->orderby('created_at', 'ASC')
                ->paginate($per_page);
        }

        return (new static)->successMessage($orders, '200');
    }

    public static function viewOrderDetails($order_id)
    {
        $order_detail = Order::with(['orderDetails' => function ($q) {
            $q->select(
                'order_id',
                'product_id',
                'count',
                'products.name as product_name',
                'product_types.id as product_type_id',
                'product_types.name as product_type_name'
            )
                ->join('products', 'products.id', '=', 'product_id')
                ->join('product_types', 'product_types.id', 'products.product_type_id');
        }])
            ->withCount('orderDetails')
            ->with('companyUser')
            ->where('id', $order_id)->get()[0];

        return (new static)->successMessage($order_detail, '200');
    }

    public static function assignOrderToDriver(Request $request)
    {
        $curr_user = Auth::guard('company-api')->user();
        $order = Order::find($request->order_id);
        $status = 1;

        $order->update($request->all());

        return (new static)->successMessage($order, '200');
    }

    public static function cancelOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->update($request->all());

        return (new static)->successMessage($order, '200');
    }

    public static function viewRetailDealerOrders($retail_dealer_id)
    {
        $per_page = request()->per_page ?? 10;
        $orders = Order::with('companyUser')
            ->withCount('orderDetails')
            ->where('company_id', '=', Auth::guard('company-api')->user()->company_id)
            ->where('retail_dealer_id', '=', $retail_dealer_id)
            ->paginate($per_page);
        return (new static)->successMessage($orders, '200');
    }
}
// 'company_id',
//         'retail_dealer_id',
//         'company_user_id',
//         'status',
//         'total_price'