<?php

namespace App\Domain\User;

use App\Domain\General\ServiceListParams;


interface UserRepositoryInterface
{
    public function save(User $user): bool;

    public function delete(int $id): bool;

    /**
     * @param int $id
     * @return User|false
     */
    public function getById(int $id);

    /**
     * @param string $email
     * @return User|false
     */
    public function getByEmail(string $email);

    public function getDuplicateField(User $user): ?string;

    public function list(ServiceListParams $params): array;

    public function getError(): string;


}
