<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function listAll()
    {
        return $this->user->with('wallet')->all();
    }

    public function getUser($params)
    {
        try {
            $user = $this->user->query();
            if (isset($params['email'])) {
                $user->where('email', $params['email']);
            }
            if (isset($params['cpf_cnpj'])) {
                $user->where('cpf_cnpj', $params['cpf_cnpj']);
            }
            return $user->with('wallet')->get()->first();
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }
    }

    public function store($params)
    {
        try {
            $user = $this->user->fill($params);
            $user->save();
            return $user;
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }
    }

    public function giveRoleforUser($user, $role)
    {
        try {
            $user->assignRole($role);
            return $user;
        } catch (\Exception $exception) {
            return response()->json(['codigo' => $exception->getCode(),
                'mensagem' => $exception->getMessage()]);
        }
    }
}
