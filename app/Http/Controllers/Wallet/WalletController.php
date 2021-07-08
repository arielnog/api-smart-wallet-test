<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletPostRequest;
use App\Services\WalletService;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function balance()
    {
        try {
            $balance = $this->walletService->getBalance(auth()->user()->id);
            return response()->json($balance,200);
        } catch (HttpResponseException $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function deposit(WalletPostRequest $request)
    {
        $request->validated();
        try {
            $wallet = $this->walletService->doDeposit($request->all(), auth()->user());
            return response()->json(['mensagem' => 'Deposito realizado com sucesso!'], 201);
        } catch (HttpResponseException $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function transfer(WalletPostRequest $request)
    {
        $request->validated();
        try {
            $wallet = $this->walletService->doTransfer($request->all(), auth()->user());
            return response()->json($wallet, 201);
        } catch (HttpResponseException $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
