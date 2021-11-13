<?php

namespace App\Domain\Event;

use App\Domain\Service\Payload;

interface EventUserServiceInterface
{
    public function addUser(int $userId, array $data): Payload;

    public function removeUser(int $userId, array $data): Payload;
}
