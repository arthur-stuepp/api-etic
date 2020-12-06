<?php


namespace App\Domain\UserEvent;


interface IUserEventRepository
{
    public function add(UserEvent $userEvent);

    public function remove(UserEvent $userEvent);

    public function  list();

    public function countEventusers(int $event);

    public function getUserEvent(UserEvent $userEvent);
}