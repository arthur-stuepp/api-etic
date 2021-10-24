<?php

namespace App\Domain\User;

use App\Domain\User\User;
use App\Domain\IRepository;
use App\Domain\Services\ServiceListParams;;

interface IUserRepository
{
    public function save(User $user): bool;

    public function delete(int $id): bool;

    /*
    * @return User|false
    */
    public function getById(int $id);

    public function list(ServiceListParams $params): array;
    
}
