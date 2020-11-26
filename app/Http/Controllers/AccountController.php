<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountController extends Controller
{

    public function reset()
    {
        Account::truncate();
        return "OK";
    }

    public function balance(Request $request)
    {
        try {
            return Account::getBalance($request->account_id);
        } catch (ModelNotFoundException $e) {
            return response(0, 404);
        }
    }

    public function event(Request $request)
    {
        try {
            switch ($request->type):
                case 'withdraw':
                    Account::withdraw($request->origin, $request->amount);
                    return response()->json(["origin" => ["id" => $request->origin, "balance" => Account::getBalance($request->origin)]], 201);
                    break;
                case 'deposit':
                    Account::deposit($request->destination, $request->amount);
                    return response()->json(["destination" => ["id" => $request->destination, "balance" => Account::getBalance($request->destination)]], 201);
                    break;
                case 'transfer':
                    Account::transfer($request->origin, $request->destination, $request->amount);
                    return response()->json([
                        "origin" => ["id" => $request->origin, "balance" => Account::getBalance($request->origin)],
                        "destination" => ["id" => $request->destination, "balance" => Account::getBalance($request->destination)]
                    ], 201);
                    break;
            endswitch;
        } catch (ModelNotFoundException $e) {
            return response(0, 404);
        }
    }
}
