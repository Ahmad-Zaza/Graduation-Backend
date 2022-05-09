<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('login', 'signUp', 'guard');
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

        $token = Auth::guard('company-api')->attempt($credentials);
        // return response()->json($token);
        if (!$token) {
            return response()->json([
                'result' => 'Invalid Input',
                'code' => '400',
            ], 400);
        }
        $comp_user = Auth::guard('company-api')->user();
        $comp_user['token'] = $token;
        $comp_user['company'] = Company::find($comp_user->company_id);

        return response()->json([
            'result' => $comp_user,
            'code' => '200'
        ], 200);
    }

    public function signUp(Request $request)
    {
        $comp_user = CompanyUser::where('username', $request->username)
            ->where('company_id', $request->company_id)
            ->first();
        if ($comp_user) {
            return response()->json([
                'result' => null,
                'msg' => 'this username has been taken before',
                'code' => '414'
            ], 414);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $comp_user = CompanyUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'user_type' => 1,
            'phone_number' => $request->phone_number,
            'email' => $request->phone_number,
            'password' => $request->password,
            'company_id' => $request->company_id
        ]);

        $token = JWTAuth::fromUser($comp_user);
        $comp_user['token'] = $token;
        return response()->json([
            'result' => $comp_user,
            'code' => '200'
        ], 200);
    }

    public function logout()
    {
        Auth::guard('company-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function guard()
    {
        return response()->json([
            'return' => Auth::guard('company-api'),
        ]);
    }
}