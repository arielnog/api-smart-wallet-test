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

    public function typeTransaction()
    {
        return $this->belongsTo(TypeTransaction::class);
    }

    public function payeer()
    {
        return $this->belongsTo(User::class, 'id', 'payeer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'id', 'payee_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
