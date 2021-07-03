<?php

namespace App\Domain\Event;

use App\Domain\IRepository;

interface IEventRepository extends IRepository
{
    public function save(Event $event):bool;

    /*
    *@return Event|false
    */
    public function getById(int $id);
}
