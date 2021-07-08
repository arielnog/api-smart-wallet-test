<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'payeer_id',
        'payee_id',
        'value',
        'type_transaction_id',
        'message'
    ];
}
