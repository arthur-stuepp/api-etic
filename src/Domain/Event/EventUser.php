<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;

class EventUser extends Entity
{

    public int $event;

    public int $user;

    public string $team;

    public bool $waitlist;

    public bool $cheking;


}
