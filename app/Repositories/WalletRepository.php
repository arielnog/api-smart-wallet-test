<?php


namespace App\Repositories;


use App\Models\Wallet;

class WalletRepository
{
    private $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function create($userId)
    {
        try {
            $wallet = $this->wallet->create(['user_id' => $userId]);
            return $wallet;
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }
    }

}
