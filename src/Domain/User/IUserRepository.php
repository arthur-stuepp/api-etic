<?php

namespace App\Domain\User;

use App\Domain\User\User;

interface IUserRepository
{
    public function create(User $user);

    public function getByEmail(string $email);

    public function getById(int $id);

    public function delete(int $id);

    public function getByTaxId(string $taxId);
}
