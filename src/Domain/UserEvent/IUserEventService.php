<?php


namespace App\Domain\UserEvent;


interface IUserEventService
{
    public function create(int $user, int $event,array $data);

    public function update(int $user, int $event, array $data);

    public function delete(int $user, int $event);

    public function list(?int $user, ?int $event);

}