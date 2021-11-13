<?php

namespace App\Domain\Event;

use App\Domain\ServicePayload;

interface EventUserServiceInterface
{
    public function addUser(int $userId, array $data): ServicePayload;

    public function removeUser(int $userId, array $data): ServicePayload;
}
