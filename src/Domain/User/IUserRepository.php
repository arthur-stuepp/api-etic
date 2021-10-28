<?php

namespace App\Domain\User;

use App\Domain\General\Interfaces\IUniquiProperties;
use App\Domain\General\Interfaces\IRepository;


interface IUserRepository extends IRepository
{
    public function save(User $user): bool;

    public function delete(int $id): bool;


    /**
     * @param int $id
     * @return User|false
     */
    public function getById(int $id);

    public function getDuplicateField(IUniquiProperties $properties): ?string;


}
