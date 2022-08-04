<?php

namespace App\Traits;

use App\Models\DoctorModels\Doctor;
use Illuminate\Support\Facades\Auth;

trait QueryTrait
{
    public function doPagination(array $params, $query)
    {
        $query->orderBy($params['order_by'] ?? 'id', $params['order_sort'] ?? 'desc');
        return $query->paginate($params['per_page'] ?? 10);
    }

    public function checkUserToken($auth_user, $user)
    {
        if ($auth_user == $user) {
            return true;
        }
        return false;
    }

    public function errorMessage($data, $code, $msg = '')
    {
        return response()->json([
            'resutl' => $data,
            'code' => $code,
            'msg' => $msg
        ], 404);
    }

    public function successMessage($data, $code)
    {
        return response()->json([
            'result' => $data,
            'code' => $code,
        ], 200);
    }

    public function AdminsuccessMessage($data)
    {
        return response()->json([
            'result' => $data,
        ], 200);
    }
    public function sendNotification($token)
    {
        // return response(request()->token);
        $data = [
            "registration_ids" => [$token],
            "notification" => [
                "body"  => 'this is test body',
                "title" => 'title test',
            ],
            "data" => [
                "type" => "type-test",
                "id" => "this is test id"
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

        // return true;
        return response($data);
    }
}
