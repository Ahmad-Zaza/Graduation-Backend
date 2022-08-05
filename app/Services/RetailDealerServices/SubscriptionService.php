<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Subscribe;
use App\Models\notifications;
use App\Models\RetailDealersModel\RetailDealer;
use App\Models\RetailDealersModel\SubscribeRequest;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SubscriptionService
{
    use QueryTrait;

    public static function getAllCompanies($name)
    {
        $companies = Company::where('name', $name)
            ->limit(10);
        return (new static)->successMessage($companies, '200');
    }

    public static function sendSubscribeRequest(Request $request)
    {

        if ((new static)->checkNotExistingRequest($request->company_id, $request->retail_dealer_id)) {
            // return response($this->checkNotExistingRequest($request->company_id, $request->retail_dealer_id));
            return (new static)->errorMessage(null, '414', 'request sended before !!');
        }

        $subRequest = SubscribeRequest::create([
            'company_id' => $request->company_id,
            'retail_dealer_id' => $request->retail_dealer_id
        ]);
        $subRequest['status'] = Config::get('constants.retailDealer.subscribeRequest.pending');
        $subRequest['retail-dealer'] = $subRequest->retailDealer()->get();
        $retail_dealer = RetailDealer::find($request->retail_dealer_id);
       /////////////////////////////// send notifications
       $title = "You have new subscribe request!";
       $body = "A new subscribe request from " . $retail_dealer->first_name  . " " . $retail_dealer->last_name . "";
       $type = "subscribe";

       $admins = CompanyUser::where('company_id', $request->company_id)
       ->where('user_type', Config::get('constants.company.users.admin_type'))->get();
       foreach($admins as $admin){
        //    (new static)->sendNotification($admin->firebasetoken, $title, $body,  $type, $admin->id); //send notification
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
           $notification->type = "Dealer";
           $notification->save();
       }
       ////////////////////////////////////////////

        return (new static)->successMessage($subRequest, '200');
    }

    public function checkNotExistingRequest($company_id, $ret_dea_id)
    {
        $subRequest = SubscribeRequest::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();
        $subRequest1 = Subscribe::where('company_id', $company_id)
            ->where('retail_dealer_id', $ret_dea_id)
            ->first();

        if ($subRequest || $subRequest1) {
            return true;
        }
        return false;
    }
}
