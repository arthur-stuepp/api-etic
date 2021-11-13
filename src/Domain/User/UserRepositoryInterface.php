<?php

namespace App\Domain\User;

use App\Domain\General\ServiceListParams;

interface UserRepositoryInterface
{
    public function save(User $user): bool;

    public function delete(int $id): bool;

    public function getById(int $id): ?User;

    public function getByEmail(string $email): ?User;

    public function getDuplicateField(User $user): ?string;

    public function list(ServiceListParams $params): array;

    public function getError(): string;
}
