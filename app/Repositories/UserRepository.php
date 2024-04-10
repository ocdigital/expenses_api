<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all(): mixed
    {
        return User::all();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find($id): mixed
    {
        return User::find($id);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
