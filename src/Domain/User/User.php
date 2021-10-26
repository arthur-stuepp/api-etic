<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\City\City;
use App\Domain\Entity;
use App\Domain\IHasUniquiProperties;
use App\Domain\School\School;
use DateTime;

class User extends Entity implements IHasUniquiProperties
{
    public const TYPE_ADMIN = 1;
    public const TYPE_USER = 2;

    public int $id;

    public int $type = self::TYPE_USER;

    public string $name;

    public string $address;

    public City $city;

    public string $email;

    public DateTime $birthday;

    public ?string $company;

    public School $school;

    public bool $disability=false;

    public string $password;

    public string $taxId;

    public ?int $indication;

    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        if (isset($json['password'])) {
            unset($json['password']);

        }
        return $json;
    }

    public function getFields(): array
    {
        return ['email', 'taxId'];
    }
}
