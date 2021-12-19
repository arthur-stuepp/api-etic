<?php

namespace App\Domain\Event;

use App\Domain\RepositoryInterface;
use App\Infrastructure\Repository\EntityParams;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function save(Event $event): bool;

    public function getById(int $id): ?Event;
    
    public function listEventUser(int $event, EntityParams $params): array;
}
