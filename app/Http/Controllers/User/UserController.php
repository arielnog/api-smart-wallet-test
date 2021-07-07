<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use App\Services\UserServices;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(Request $request)
    {
        dd($request->all());
        try {
            $user = $this->userService->store($request->all());
            return $user;
        } catch (HttpClientException $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
