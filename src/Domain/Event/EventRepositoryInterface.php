<?php

namespace App\Domain\Event;

use App\Domain\General\Interfaces\RepositoryInterface;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function save(Event $event):bool;

    /*
    *@return Event|false
    */
    public function getById(int $id);
}
