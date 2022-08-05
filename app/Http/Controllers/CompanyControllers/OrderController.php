<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Order;
use App\Models\notifications;
use App\Models\RetailDealersModel\RetailDealer;
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
                            'status',
                            Config::get('constants.company.order.delivering')
                        );
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

        /////////////////////////////// send notifications to admins
        $title = "You have new driver on live!";
        $body = "See that!";
        $type = "order-live-admin";
        $company_id = Auth::guard('company-api')->user()->company_id;
        $admins = CompanyUser::where('company_id', $company_id)
            ->where('user_type', Config::get('constants.company.users.admin_type'))->get();
        return response()->json(["admins" => $admins]);
        foreach ($admins as $admin) {
            // $this->sendNotification($admin->firebasetoken, $title, $body,  $type, $admin->id); //send notification
            $data = [
                "registration_ids" => [$admin->firebasetoken],
                "notification" => [
                    "body"  => $body,
                    "title" => $title,
                ],
                "data" => [
                    "type" => $type,
                    "id" => $order->id
                ],
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=AAAAxbkUDBc:APA91bHL9Z4tWphs2HKNWJ4D9EUcinadhgW2BHCVfrkDPtkhOXMM8Z1QzyZSjuJzh8TiAsChM0rTIAa2ri35SJwjESmZO5A-Oi3a8TssSpNWNhVPzFJg9kVzYgw7jNn7RPRP8G6rkuUd';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);

            ///////////
            $notification = new notifications();
            $notification->user_id = $admin->id;
            $notification->title = $title;
            $notification->body = $body;
            $notification->type = "Driver";
            $notification->save();
        }
        ////////////////////////////////////////////

        // send notifications to dealers
        // soon ....

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
        // send notficatinos
        $driver = Auth::guard('company-api')->user();
        $title = "Order completed!";
        $body = "" . $driver->first_name  . " " . $driver->last_name . " completed the order, see that!";
        $type = "order-completed-admin";

        $admins = CompanyUser::where('company_id', $driver->company_id)
            ->where('user_type', Config::get('constants.company.users.admin_type'))->get();
        foreach ($admins as $admin) {
            ////////////    send notification to admins
            $data = [
                "registration_ids" => [$admin->firebasetoken],
                "notification" => [
                    "body"  => $body,
                    "title" => $title,
                ],
                "data" => [
                    "type" => $type,
                    "id" => $request->retail_dealer_id
                ],
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=AAAAxbkUDBc:APA91bHL9Z4tWphs2HKNWJ4D9EUcinadhgW2BHCVfrkDPtkhOXMM8Z1QzyZSjuJzh8TiAsChM0rTIAa2ri35SJwjESmZO5A-Oi3a8TssSpNWNhVPzFJg9kVzYgw7jNn7RPRP8G6rkuUd';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $notification = new notifications();
            $notification->user_id = $admin->id;
            $notification->title = $title;
            $notification->body = $body;
            $notification->type = "Driver";
            $notification->save();
        }
        ///////////////////
        ////////////////// send notification to retail dealer
        $retail_dealer = RetailDealer::find($order->retail_dealer_id);
        $data = [
            "registration_ids" => [$retail_dealer->firebasetoken],
            "notification" => [
                "body"  => $body,
                "title" => $title,
            ],
            "data" => [
                "type" => "order-completed-dealer",
                "id" => $order->id
            ],
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=AAAAxbkUDBc:APA91bHL9Z4tWphs2HKNWJ4D9EUcinadhgW2BHCVfrkDPtkhOXMM8Z1QzyZSjuJzh8TiAsChM0rTIAa2ri35SJwjESmZO5A-Oi3a8TssSpNWNhVPzFJg9kVzYgw7jNn7RPRP8G6rkuUd';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $notification = new notifications();
        $notification->user_id = $admin->id;
        $notification->title = $title;
        $notification->body = $body;
        $notification->type = "Driver";
        $notification->save();
        /////

        return response()->json([
            'msg' => 'order has been updated successfully',
            'code' => '200'
        ]);
    }
}
