<?php

namespace App\Domain\User;

use App\Domain\RepositoryInterface;

interface UserRepositoryInterfaceInterface extends RepositoryInterface
{
    public function save(User $user): bool;

    public function getById(int $id): ?User;

    public function getByEmail(string $email): ?User;

    public function getDuplicateField(User $user): ?string;
}
