<?php

namespace App\Domain\Event;

use App\Domain\Service\Payload;

interface EventUserServiceInterface
{
    public function addUser(int $userId, array $data): Payload;

    public function updateUser(int $userId, array $data): Payload;

    public function removeUser(int $eventId, int $userId): Payload;

    public function readUser(int $eventId, int $userId): Payload;

}
