<?php

namespace App\Domain\User;

use App\Domain\General\Interfaces\RepositoryInterface;
use App\Domain\General\Interfaces\UniquiPropertiesInterface;


interface UserRepositoryInterface extends RepositoryInterface
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

    public function getDuplicateField(UniquiPropertiesInterface $properties): ?string;


}
