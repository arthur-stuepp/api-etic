<?php

namespace App\Domain\UserEvent;

use App\Domain\ServiceListParams;

interface IUserEventRepository
{
    public function save(UserEvent $userEvent): bool;

    public function remove(UserEvent $userEvent): bool;

    public function list(ServiceListParams $params): array;

    /*
    *@return User|false
    */
    public function getUserById(int $user);

    /*
    *@return Event|false
    */
    public function getEventById(int $user);

    public function getLastSaveId(): int;

    public function getLastError(): string;
}
