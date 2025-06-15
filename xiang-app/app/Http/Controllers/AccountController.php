<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Routing\Controller as BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AccountController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Helper to test with the token is valid
    protected function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 401);
            }
            return $user;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }
    }
    
    //List of accounts
    public function list()
    {
        $user = $this->getAuthenticatedUser();
        if($user instanceof JsonResponse) return $user;

        return Account::where('user_id', $user->id)->get();
    }

    //Storage a new account
    public function add(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $data = $request->all();
        $data['user_id'] = auth()->guard('api')->user()->id;
        $data['password'] = Crypt::encryptString($request->input('password'));
        Account::create($data);
        return response()->json(['message' => 'Account created successfully'], 201);
    }

    //Display a specific account
    public function show(string $id )
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        return Account::where('id', $id)
            ->where('user_id',auth()->guard('api')->user()->id)
            ->firstOrFail();
    }

    //Update a specific account
    public function edit(Request $request, string $id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $account = Account::where('id', $id)->where('user_id',auth()->guard('api')->user()->id)->firstOrFail();
        $account->update($request->all());
        return $account;
    }

    //Delete a specific account
    public function destroy(string $id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) return $user;

        $account = Account::where('id', $id)->where('user_id', auth()->guard('api')->user()->id)->firstOrFail();
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }

    //Decrypt a password to show on screen
    public function decrypt(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if($user instanceof JsonResponse) return $user;

        $validator = Validator::make($request->all(),[
            'password' => [
                'required',
                'string',
                'min:6',
            ],
            'accountpassword' =>[
                'required',
                'string',
                'min:6',
            ],
        ]);
        if ($validator->fails()) return response()->json([
            'error' => $validator->errors()], 422);

        $password = $request->input('password');
        $accountPassword = $request->input('accountpassword');
        $userPassword = $user->password;
        if(!Hash::check($password,$userPassword)) return response()->json([
            'message' => 'Password is incorrect',
        ],401);

        return response()->json([
            'decrypted' => Crypt::decryptString($accountPassword)
        ]);
    }
}
