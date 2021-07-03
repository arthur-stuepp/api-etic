<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Entity;

class User extends Entity
{

    public int $id;

    public string $name;

    public string $address;

    public int $city;

    public string $email;

    public int $birthday;

    public string $company;

    public int $school;

    public int $disability;

    public string $password;

    public string $taxId;

    public int $indication;

}
