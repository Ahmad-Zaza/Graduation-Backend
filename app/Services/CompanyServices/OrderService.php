<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\Order;
use App\Traits\QueryTrait;

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
        $orders = Order::with('orderDetails')
            ->where('company_id', $company_id)
            ->where('company_user_id', '=', null)
            ->paginate($per_page);

        return (new static)->successMessage($orders, '200');
    }
}
// 'company_id',
//         'retail_dealer_id',
//         'company_user_id',
//         'status',
//         'total_price'