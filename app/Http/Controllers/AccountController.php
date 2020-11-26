<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{

    public function __construct()
    {
    }

    public function reset()
    {
        Account::truncate();
        return "OK";
    }

    public function balance(Request $request)
    {
        try {
            $Account = Account::where('id', $request->account_id)->firstOrFail();
            return $Account->getBalance();
        } catch (ModelNotFoundException $e) {
            return response(0, 404);
        }
    }

    public function event(Request $request)
    {
        try {
            switch ($request->type):
                case 'withdraw':
                    $Account = $this->_withdraw($request->origin,$request->amount);
                    return response()->json(["origin" => ["id" => $Account->id, "balance" => $Account->getBalance()]], 201);
                    break;
                case 'deposit':
                    $Account = $this->_deposit($request->destination,$request->amount);
                    return response()->json(["destination" => ["id" => $Account->id, "balance" => $Account->getBalance()]], 201);
                    break;
                case 'transfer':
                    list($AccountOrigin, $AccountDestination) = $this->_transfer($request->origin, $request->destination,$request->amount);
                    return response()->json([
                        "origin" => ["id" => $AccountOrigin->id, "balance" => $AccountOrigin->balance],
                        "destination" => ["id" => $AccountDestination->id, "balance" => $AccountDestination->balance]
                    ], 201);
                    break;
            endswitch;
        } catch (ModelNotFoundException $e) {
            return response(0, 404);
        }
    }

    private function _withdraw($origin, $amount){
        $Account = Account::where('id', $origin)->firstOrFail();
        $Account->balance -= $amount;
        if($Account->save())
            return $Account;
    }

    private function _deposit($destination, $amount){
        $Account = Account::firstOrNew(['id' => $destination]);
        $Account->id = $destination;
        $Account->balance += $amount;
        if($Account->save())
            return $Account;
    }

    private function _transfer($origin, $destination, $amount){
        $AccountOrigin      = $this->_withdraw($origin,$amount);
        $AccountDestination = $this->_deposit($destination,$amount);
        return [$AccountOrigin, $AccountDestination];
    }
}
