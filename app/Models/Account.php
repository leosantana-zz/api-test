<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'balance'
    ];

    static function getBalance($id)
    {
        $Account = Account::where('id', $id)->firstOrFail();
        return intval($Account->balance);
    }

    static function withdraw($id, $amount)
    {
        $Account = Account::where('id', $id)->firstOrFail();
        $Account->balance -= $amount;
        return $Account->save();
    }

    static function deposit($id, $amount)
    {
        $Account = Account::firstOrNew(['id' => $id]);
        $Account->id = $id;
        $Account->balance += $amount;
        return $Account->save();
    }

    static function transfer($originId, $destinationId, $amount)
    {
        Account::withdraw($originId, $amount);
        Account::deposit($destinationId, $amount);
    }
}
