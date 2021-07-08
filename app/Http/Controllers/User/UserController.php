<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPostRequest;
use App\Services\UserService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(UserPostRequest $request)
    {
        try {
            $user = $this->userService->store($request->all());
            return $user;
        } catch (HttpClientException $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
