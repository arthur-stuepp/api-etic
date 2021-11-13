<?php

namespace App\Domain\Event;

use App\Domain\General\ServiceListParams;

interface EventRepositoryInterface
{
    public function save(Event $event): bool;

    public function getById(int $id): ?Event;

    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;

    public function getError(): string;
}
