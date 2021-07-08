<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\UtilService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository,
        $utilService,
        $walletRepository;

    public function __construct(UtilService $utilService, UserRepository $userRepository,WalletRepository $walletRepository)
    {
        $this->userRepository = $userRepository;
        $this->utilService = $utilService;
        $this->walletRepository = $walletRepository;
    }

    public function listAll()
    {
        return $this->userRepository->listAll();
    }

    public function getUser($params)
    {
        if (isset($params['cpf_cnpj'])) {
            $params['cpf_cnpj'] = $this->utilService->dealInput($params['cpf_cnpj']);
        }
        $filterParams = array_filter($params, function ($arr) {
            return !is_null($arr);
        });

        return $this->userRepository->getUser($filterParams);
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $params['cpf_cnpj'] = $this->utilService->dealInput($params['cpf_cnpj']);
            $params['password'] = Hash::make($params['password']);

            $userCreate = $this->userRepository->store($params);
            if (is_null($userCreate)) {
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao criar usuário'
                ];
            }

            $roleCreate = $this->userRepository->giveRoleforUser($userCreate, $params['role']);
            if (is_null($roleCreate)) {
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao atribuir perfil ao usuário'
                ];
            }

            $walletCreate = $this->walletRepository->create($userCreate->id);
            if (is_null($walletCreate)) {
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao criar carteira do usuário'
                ];
            }

            return ['mensagem' => 'Usuário Criado com Sucesso!'];
        });
    }

}
