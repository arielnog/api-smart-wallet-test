<?php


use App\Repositories\UserRespository;
use App\Service\UtilServices;
//use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository,
        $utilService,
        $walletService;

    public function __construct(UtilServices $utilService, UserRespository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->utilService = $utilService;
    }

    public function listAll()
    {
        return $this->userRepository->listAll();
    }

    public function getUser($params)
    {
        if (isset($params['cpf_cnpj'])){
            $params['cpf_cnpj'] = $this->utilService->dealInput($params['cpf_cnpj']);
        }
        $filterParams = array_filter($params, function ($arr){
            return !is_null($arr);
        });

        return $this->userRepository->getUser($filterParams);
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $params['cpf_cnpj'] = $this->utilService->dealInput($params['cpf_cnpj']);
            $params['password'] = Hash::make($params['password']);

            $userCreate =  $this->userRepository->store($params);
            if (is_null($userCreate)){
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao criar usuário'
                ];
            }

            $roleCreate = $this->userRepository->giveRoleforUser($userCreate,$params['role']);
            if (is_null($roleCreate)){
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao atribuir perfil ao usuário'
                ];
            }

            $walletCreate = $this->walletService->store($userCreate->id);
            if(is_null($walletCreate)){
                DB::rollBack();
                return [
                    'mensagem' => 'Falha ao criar carteira do usuário'
                ];
            }

            return ['mensagem' => 'Usuário Criado com Sucesso!'];
        });
    }

}
