<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Rules\StrongPassword;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(($request->all()), [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if(!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Email or password is incorrect'], 401);
        }
        return $this->createNewToken($token);

    }
    public function logout(Request $request)
    {
        return null;
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                Rule::email()->validateMxRecord(),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                new StrongPassword,
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => JWTAuth::factory()->getTTL()*60,
            'user' => JWTAuth::user()
        ], 200);
    }
}
