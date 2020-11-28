<?php

declare(strict_types=1);

namespace App\Domain\UserHackthon;

use App\Domain\Entity;

class UserHackthon extends Entity
{

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public int $user;

    public ?string $team;

    public int $cheking;

    public int $capacity;

    public int $waitlist;
}
