<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Order;
use App\Models\CompanyModels\OrderDetail;
use App\Models\CompanyModels\Product;
use App\Models\notifications;
use App\Models\RetailDealersModel\RetailDealer;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService.
 */
class OrderService
{
    use QueryTrait;

    public static function retailDealerCompanies()
    {
        $per_page = request()->per_page ?? 10;
        $companies = DB::table('companies')
            ->join('subscribes', function ($join) {
                $retail_dealer = Auth::guard('retail-dealer-api')->user();
                $join->on('subscribes.company_id', '=', 'companies.id')
                    ->where('subscribes.retail_dealer_id', '=', $retail_dealer->id);
            })
            ->select('companies.*')
            ->paginate($per_page);

        return (new static)->successMessage($companies, '200');
    }

    public static function companiesQuerySearch()
    {
        $searchText = request()->searchText;
        $companies = DB::table('companies')
            ->join('subscribes', function ($join) {
                $retail_dealer = Auth::guard('retail-dealer-api')->user();
                $join->on('subscribes.company_id', '=', 'companies.id')
                    ->where('subscribes.retail_dealer_id', '=', $retail_dealer->id);
            })
            ->where('companies.name', 'like', '%' . $searchText . '%')
            ->limit(5)
            ->get();
        return (new static)->successMessage($companies, '200');
    }

    public static function companyProducts($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $products = DB::table('products')
            ->join('product_types', function ($join) use ($company_id) {
                $join->on('product_types.id', '=', 'products.product_type_id')
                    ->where('product_types.company_id', '=', $company_id);
            })
            ->join('categories', 'categories.id', 'products.category_id')
            ->select(
                'products.*',
                'product_types.name as product_type_name',
                'categories.name as category_name'
            )
            ->paginate($per_page);
        return (new static)->successMessage($products, '200');
    }

    public static function makeOrder(Request $request)
    {
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
        (new static)->sendNotificationToAdmin($request->retail_dealer_id, $request->company_id);
        return (new static)->successMessage($order, '200');
    }

    public static function viewAllOrders()
    {
        $per_page = request()->per_page ?? 10;

        $orders = Order::with('company')
            ->with('companyUser')
            ->withCount('orderDetails')
            ->where('retail_dealer_id', '=', Auth::guard('retail-dealer-api')->user()->id)
            ->paginate($per_page);

        return (new static)->successMessage($orders, '200');
    }

    public static function viewOrderDetails($order_id)
    {
        $order_detail = Order::with(['orderDetails' => function ($q) {
            $q->select('order_id', 'product_id', 'count', 'products.name as product_name')
                ->join('products', 'products.id', '=', 'product_id');
        }])
            ->with('companyUser')
            ->where('id', $order_id)->get()[0];
        return (new static)->successMessage($order_detail, '200');
    }

    public static function viewMyOrdersInCompany($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $status = request()->status ?? null;
        if (is_null($status)) {
            $myOrders = Order::with('companyUser')
                ->with('companyUser')
                ->where('company_id', '=', $company_id)
                ->where('retail_dealer_id', '=', Auth::guard('retail-dealer-api')->user()->id)
                ->paginate($per_page);
        } else {
            $myOrders = Order::with('companyUser')
                ->with('companyUser')
                ->where('company_id', '=', $company_id)
                ->where('retail_dealer_id', '=', Auth::guard('retail-dealer-api')->user()->id)
                ->where('status', '=', $status)
                ->paginate($per_page);
        }

        return (new static)->successMessage($myOrders, '200');
    }

    public function sendNotificationToAdmin($retail_dealer_id, $company_id)
    {
        $retail_dealer = RetailDealer::find($retail_dealer_id);
        $title = "New order!";
        $body = "You have a new order from " . $retail_dealer->first_name . " " . $retail_dealer->last_name . "";
        $type = "order";
        $admins = CompanyUser::where('company_id', $company_id)
        ->where('user_type', Config::get('constants.company.users.admin_type'))->get();

        foreach ($admins as $admin) {
            (new static)->sendNotification($admin->firebasetoken, $title, $body,  $type, $admin->id); //send notification
            $notification = new notifications();
            $notification->user_id = $admin->id;
            $notification->title = $title;
            $notification->body = $body;
            $notification->type = "Dealer";
            $notification->save();
        }
    }
}
