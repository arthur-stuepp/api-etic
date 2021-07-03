<?php

declare(strict_types=1);

namespace App\Domain\User;

use DateTime;
use App\Domain\Entity;
use App\Domain\City\City;
use App\Domain\School\School;

class User extends Entity
{

    public int $id;

    public string $name;

    public string $address;

    public City $city;

    public string $email;

    public DateTime $birthday;

    public ?string $company;

    public School $school;

    public bool $disability;

    public string $password;

    public string $taxId;

    public int $indication;

}
