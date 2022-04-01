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

    public function notification()
    {
        //$admin1 = Doctor::find($id);
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        //$admin = Auth()->guard('admin-api')->user();
        $token = request()->bearerToken();

        // if($admin1 != $admin){
        //     return response()->json([
        //         'result' => null,
        //         'msg' => 'UnAuthenticate User!!'
        //     ]);
        // }

        if (!Auth::guard('patient-api')->check()) {
            return response()->json([
                'result' => null,
                'msg' => 'you are not authentication',
            ]);
        }


        $notification = [
            'title' => 'notification title',
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=Legacy server key',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        // return true;
        return response($notification);
    }
}