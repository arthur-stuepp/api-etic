<?php

namespace App\Domain\Event;

use App\Domain\RepositoryInterface;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function save(Event $event): bool;

    public function getById(int $id): ?Event;
}
