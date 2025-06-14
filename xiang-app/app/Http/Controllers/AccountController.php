<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    
    //List of accounts
    public function index()
    {
        return Account::all();
    }

    //Storage a new account
    public function store(Request $request)
    {
        Account::create($request->all());
        return response()->json(['message' => 'Account created successfully'], 201);
    }

    //Display a specific account
    public function show(string $id)
    {
        return Account::findOrFail($id);
    }

    //Update a specific account
    public function update(Request $request, string $id)
    {
        $account = Account::findOrFail($id);
        $account->update($request->all());
        return $account;
    }

    //Delete a specific account
    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }
}
