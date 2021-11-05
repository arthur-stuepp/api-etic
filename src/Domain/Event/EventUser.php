<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\User\User;

class EventUser extends AbstractEntity
{
    protected int $event;
    protected User $user;
    protected ?string $team;
    protected bool $waitlist = false;
    protected bool $cheking = false;
    
    public function setWaitlist(bool $waitlist): void
    {
        $this->waitlist = $waitlist;
    }

    public function setCheking(bool $cheking): void
    {
        $this->cheking = $cheking;
    }

    public function getUser(): User
    {
        return $this->user;
    }


}
