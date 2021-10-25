<?php

namespace App\Domain\User;

use App\Domain\IHasUniquiProperties;
use App\Domain\IRepository;


interface IUserRepository extends IRepository
{
    public function save(User $user): bool;

    public function delete(int $id): bool;

    /*
    * @return User|false
    */
    public function getById(int $id);

    public function getDuplicateField(IHasUniquiProperties $properties): ?string;


}
