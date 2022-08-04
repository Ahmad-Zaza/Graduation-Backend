<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('assign.guard:auth')->except('index', 'login', 'sendNotification');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function sendNotification(Request $request)
    {
        // return response(request()->token);
        $data = [
            "registration_ids" => [request()->token],
            "notification" => [
                "body"  => '  هيللوووووووووووووووووووووو بس بدون vpn',
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
