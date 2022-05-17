<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['SignUp']]);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function SignUp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'result' => null,
                'code' => '414',
            ], 414);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
        //$user = Doctor::create($request->all());
        // return response($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);


        $token = JWTAuth::fromUser($user);
        $user['token'] = $token;
        return response()->json([
            'result' => $user,
            'code' => '200',
        ], 200);
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }


    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }



    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }


    public function guard()
    {
        return Auth::guard();
    }
}