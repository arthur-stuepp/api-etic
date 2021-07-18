<?php

declare(strict_types=1);

namespace App\Domain\UserEvent;

use App\Domain\Entity;
use App\Domain\User\User;
use App\Domain\Event\Event;

class UserEvent extends Entity
{

    public User $user;

    public Event $event;

    public ?string $team;

    public bool $cheking;

    public bool $waitlist;

}