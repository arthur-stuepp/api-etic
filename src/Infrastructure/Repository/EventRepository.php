<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Event\Event;
use App\Domain\Event\IEventRepository;

class EventRepository extends MysqlRepository implements IEventRepository
{
    protected function getClass(): string
    {
        return Event::class;
    }

    public function save(Event $event): bool
    {
        return $this->saveEntity($event);
    }
}
