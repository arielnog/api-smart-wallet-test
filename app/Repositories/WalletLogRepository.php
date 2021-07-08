<?php


namespace App\Repositories;


use App\Models\WalletLog;

class WalletLogRepository
{
    private $walletLog;

    public function __construct(WalletLog $walletLog)
    {
        $this->walletLog = $walletLog;
    }

    public function store($params)
    {
        try {
            $log = $this->walletLog->create($params);
            $log->save();
            return $log;
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }

    }

    public function getBalance($userId)
    {
        try {
            $wallet = $this->walletLog
                            ->where('payeer_id', $userId)
                            ->orWhere('payee_id', $userId)
                            ->get()
                            ->sum('value');
            return $wallet;
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }
    }

}
