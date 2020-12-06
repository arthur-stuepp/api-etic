<?php

namespace App\Domain\Event;

interface IEventRepository
{
    public function create(Event $event);

    public function update(Event $event);

    public function delete(int $id);

    /**
     * @param int $id
     * @return false|Event
     */
    public function getById(int $id);

}
