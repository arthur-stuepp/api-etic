<?php

declare(strict_types=1);

namespace App\Domain\Event\User;

use App\Domain\AbstractEntity;
use App\Domain\User\User;

class EventUser extends AbstractEntity
{
    protected int $event;
    protected User $user;
    protected ?string $team;
    protected bool $waitlist;
    protected bool $cheking;

    public function setCheking(bool $cheking): void
    {
        $this->cheking = $cheking;
    }

    public function setTeam(string $team): void
    {
        $this->team = $team;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
