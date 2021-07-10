<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\User;
use App\Domain\User\IUserRepository;

class UserRepository extends MysqlRepository implements IUserRepository
{
    protected function getClass(): string
    {
        return User::class;
    }
    public function save(User $user): bool
    {
        return $this->saveEntity($user);
    }
}
