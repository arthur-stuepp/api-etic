<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\EntityInterface;
use App\Domain\User\User;

class EventUser extends EntityInterface
{
    
    public int $event;
    public User $user;
    public ?string $team;
    public bool $waitlist = false;
    public bool $cheking = false;


}
