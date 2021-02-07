<?php


namespace App\Domain\UserEvent;


interface IUserEventRepository
{
    public function add(UserEvent $userEvent);

    public function remove(UserEvent $userEvent);

    public function countEventusers(int $event);

    public function getUsersByEvent(int $event);

    public function getEventsByUser(int $user);

    public function getUserEvent(UserEvent $userEvent);
}