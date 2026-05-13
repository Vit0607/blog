<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ){}

    public function changeRole(int $id, UserRole $role): bool
    {
        return $this->userRepository->updateRole($id, $role);
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($perPage);
    }
}
