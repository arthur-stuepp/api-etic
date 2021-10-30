<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\User\User;

class EventUser extends AbstractEntity
{

    protected Event $event;
    protected User $user;
    protected ?string $team;
    protected bool $waitlist = false;
    protected bool $cheking = false;

    public function updateWaitlist()
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }


    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }


}
