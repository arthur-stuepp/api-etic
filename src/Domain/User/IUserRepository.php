<?php

namespace App\Domain\User;

use App\Domain\General\Interfaces\IRepository;
use App\Domain\General\Interfaces\IUniquiProperties;


interface IUserRepository extends IRepository
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

    public function getDuplicateField(IUniquiProperties $properties): ?string;


}
