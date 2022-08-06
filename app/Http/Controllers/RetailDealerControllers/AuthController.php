<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\RetailDealersModel\RetailDealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('assign.guard:retail-dealer-api')->except('login', 'signUp', 'guard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('username', 'password');
        $token = Auth::guard('retail-dealer-api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'result' => 'Invalid Input',
                'code' => '400',
            ], 400);
        }
        $retail_dealer = Auth::guard('retail-dealer-api')->user();
        $retail_dealer['token'] = $token;

        return response()->json([
            'result' => $retail_dealer,
            'code' => '200'
        ], 200);
    }

    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:retail_dealers,username',
            'phone_number' => 'required|string',
            'email' => 'email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'longitude' => 'required',
            'latitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $retail_dealer = RetailDealer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'user_type' => 1,
            'phone_number' => $request->phone_number,
            'email' => $request->phone_number,
            'password' => $request->password,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $token = JWTAuth::fromUser($retail_dealer);
        $retail_dealer['token'] = $token;
        return response()->json([
            'result' => $retail_dealer,
            'code' => '200'
        ], 200);
    }

    public function logout()
    {
        $retail_dealer = RetailDealer::find(Auth::guard('retail-dealer-api')->user()->id);
        $retail_dealer->update([
            'firebasetoken' => null
        ]);
        Auth::guard('retail-dealer-api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
