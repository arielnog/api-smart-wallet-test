<?php

namespace App\Services;

use App\Repositories\WalletLogRepository;
use App\Repositories\WalletRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class WalletService
{
    protected $walletRepository,
        $walletLogRepository,
        $userService;

    public function __construct(WalletRepository $walletRepository, WalletLogRepository $walletLogRepository, UserService $userService)
    {
        $this->walletRepository = $walletRepository;
        $this->walletLogRepository = $walletLogRepository;
        $this->userService = $userService;
    }

    public function store($userId)
    {
        return $this->walletRepository->create($userId);
    }

    public function getBalance($userId)
    {
        return $this->walletRepositoryLog->getBalance($userId);
    }

    public function doDeposit($params, $user)
    {
        return DB::transaction(function () use ($params, $user) {

            $doDeposit = $this->doActionWithWallet($params, $user, $typeTransaction = 3);
            if (!$doDeposit) {
                DB::rollBack();
                return [
                    'mensagem' => 'Erro pra fazer o deposito'
                ];
            }
            DB::commit();
        });
    }

    public function doTransfer($params, $payeer)
    {
        return DB::transaction(function () use ($params, $payeer) {
             $valueBalance = $this->walletLogRepository->getBalance($payeer->id);
             if ($valueBalance < $params['value'] && $valueBalance <= '0') {
                 return [
                     'mensagem' => 'Você não possui saldo suficiente pra fazer a transferencia'
                 ];
             }

            $checkPayee = $this->userService->getUser($params);
            if (empty($checkPayee)) {
                return [
                    'mensagem' => 'Usuário não foi encontrado na base de dados'
                ];
            }

            $payeerWithdraw = $this->doActionWithWallet($params, $payeer, $typeTransaction = 2);
            if (!$payeerWithdraw) {
                DB::rollBack();
                return [
                    'mensagem' => 'Erro pra fazer a transferencia'
                ];
            }

            $payeeDeposit = $this->doActionWithWallet($params, $checkPayee, $typeTransaction = 1);
            if (!$payeeDeposit) {
                DB::rollBack();
                return [
                    'mensagem' => 'Erro pra fazer a transferencia'
                ];
            }

            DB::commit();

            return ['mensagem' => 'Transferencia realizada com sucesso'];
        });

        return ['mensagem' => 'Seu usuário não tem permissão de executar este recurso'];
    }

    public function doActionWithWallet($params, $user, $operation)
    {
        $paramsLog = [
            'wallet_id' => $user->wallet[0]->id,
            'type_transaction_id' => $operation,
            'message' => $params['message'] ?? null
        ];
        if ($operation == 1 || $operation == 3) {
            $paramsLog['value'] = +($params['value']);
            $paramsLog['payee_id'] = $user->id;
        } else if ($operation == 2) {
            $paramsLog['value'] = (-$params['value']);
            $paramsLog['payeer_id'] = $user->id;
        }
        return $this->walletLogRepository->store($paramsLog);
    }


}
