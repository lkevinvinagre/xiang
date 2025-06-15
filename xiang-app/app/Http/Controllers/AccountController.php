<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Routing\Controller as BaseController;

class AccountController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    //List of accounts
    public function index()
    {
        return Account::where('user_id', auth()->guard('api')->user()->id)->get();
    }

    //Storage a new account
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->guard('api')->user()->id;
        Account::create($data);
        return response()->json(['message' => 'Account created successfully'], 201);
    }

    //Display a specific account
    public function show(string $id )
    {
        return Account::where('id', $id)
            ->where('user_id',auth()->guard('api')->user()->id)
            ->firstOrFail();
    }

    //Update a specific account
    public function update(Request $request, string $id)
    {
        $account = Account::where('id', $id)->where('user_id',auth()->guard('api')->user()->id)->firstOrFail();
        $account->update($request->all());
        return $account;
    }

    //Delete a specific account
    public function destroy(string $id)
    {
        $account = Account::where('id', $id)->where('user_id', auth()->guard('api')->user()->id)->firstOrFail();
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }
}
