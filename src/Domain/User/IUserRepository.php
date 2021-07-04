<?php

namespace App\Domain\User;

use App\Domain\User\User;
use App\Domain\IRepository;

interface IUserRepository extends IRepository
{
    public function save(User $user);

    public function getById(int $id);
}
