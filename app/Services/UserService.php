<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\traits\AuthorizationTrait;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use AuthorizationTrait;

    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function all()
    {
        $authorized = $this->authorizeAdmin();

        if ($authorized) {
            return $authorized;
        }

        $users = $this->userRepository->all();

        return response()->json([
            'data' => [
                'users' => $users,
            ],
        ]);
    }

    public function show(User $user)
    {
        $authorized = $this->authorizeUser($user);

        if ($authorized) {
            return $authorized;
        }

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function create($data)
    {
        $authorized = $this->authorizeAdmin();

        if ($authorized) {
            return $authorized;
        }

        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data->all());

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function update($data, User $user)
    {
        $authorized = $this->authorizeUser($user);

        if ($authorized) {
            return $authorized;
        }

        if ($data->has('password')) {
            $data['password'] = Hash::make($data['password']);
        }

        if (! $this->userRepository->update($user, $data->all())) {
            abort(500, 'User Update Failed');
        }

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function delete(User $user)
    {
        $authorized = $this->authorizeUser($user);

        if ($authorized) {
            return $authorized;
        }

        if (! $this->userRepository->delete($user)) {
            abort(500, 'User Deletion Failed');
        }

        return response()->json([], 204);
    }
}
