<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
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

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        return $this->userService->create($validated);
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        return $this->userService->update($validated, $user);
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
