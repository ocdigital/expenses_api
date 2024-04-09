<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService, protected AuthService $authService)
    {

    }

    public function index()
    {
        return $this->userService->all();
    }

    public function show(User $user)
    {
        return $this->userService->show($user);
    }

    public function store(Request $request)
    {
        return $this->userService->create($request);
    }

    public function update(Request $request, User $user)
    {
        return $this->userService->update($request, $user);
    }

    public function destroy(User $user)
    {
        return $this->userService->delete($user);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
