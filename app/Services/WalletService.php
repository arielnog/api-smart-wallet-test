<?php

namespace App\Services;

use App\Notifications\TransactionNotify;
use App\Repositories\WalletLogRepository;
use App\Repositories\WalletRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        return $this->walletLogRepository->getBalance($userId);
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

            if ($this->checkEmailAuthorization()) {
                return [
                    'mensagem' => 'Erro ao enviar o email'
                ];
            }

            $user->notify(new TransactionNotify($doDeposit));

            return ['mensagem' => 'Deposito realizado com sucesso'];
        });
    }

    public function doTransfer($params, $user)
    {
        return DB::transaction(function () use ($params, $user) {
            $valueBalance = $this->walletLogRepository->getBalance($user->id);
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

            $payeerWithdraw = $this->doActionWithWallet($params, $user, $typeTransaction = 2);
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

            if ($this->checkEmailAuthorization()) {
                return [
                    'mensagem' => 'Erro ao enviar o email'
                ];
            }

            $user->notify(new TransactionNotify($payeerWithdraw));
            $checkPayee->notify(new TransactionNotify($payeeDeposit));

            return ['mensagem' => 'Transferencia realizada com sucesso'];
        });
    }

    public function doActionWithWallet($params, $user, $operation)
    {
        if ($this->checkActionAuthorization()) {
            return [
                'mensagem' => 'Essa operação não foi autorizada.'
            ];
        }
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


    public function checkEmailAuthorization()
    {
        $authorization = Http::get('http://o4d9z.mocklab.io/notify');

        if (!$authorization->json()['message'] == "Success") {
            return true;
        }
        return false;
    }

    public function checkActionAuthorization()
    {
        $authorization = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        if (!$authorization->json()['message'] == "Autorizado") {
            return true;
        }
        return false;
    }

}
