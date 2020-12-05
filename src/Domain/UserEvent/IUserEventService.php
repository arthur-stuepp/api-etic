<?php


namespace App\Domain\UserEvent;


interface IUserEventService
{
    public function add(int $user, int $event);

    public function remove(int $user, int $event);

}