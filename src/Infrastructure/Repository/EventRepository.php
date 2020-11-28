<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Event\Event;
use App\Domain\Event\IEventRepository;

class EventRepository extends MysqlRepository implements IEventRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'events';
        $this->class = Event::class;
    }

    public function create(Event $event)
    {
        return parent::insert($event->jsonSerialize());
    }

    /**
     * @param int $id
     * @return false|Event
     */
    public function getById(int $id)
    {
        return $this->getByField('id', $id);
    }

    public function delete(int $id): bool
    {
        return parent::delete($id);
    }

    public function update(Event $event)
    {
        // TODO: Implement update() method.
    }
}
