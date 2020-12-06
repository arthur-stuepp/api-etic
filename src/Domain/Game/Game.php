<?php

declare(strict_types=1);

namespace App\Domain\Game;

use App\Domain\Entity;

class Game extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public int $user;

    public ?string $team;

    public bool $cheking;

    public int $capacity;

    public int $waitlist;
}
