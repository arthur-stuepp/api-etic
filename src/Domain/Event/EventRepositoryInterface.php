<?php

namespace App\Domain\Event;

use App\Domain\General\ServiceListParams;

interface EventRepositoryInterface
{
    public function save(Event $event): bool;

    /**
     * @param int $id
     * @return Event|false
     */
    public function getById(int $id);

    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;

    public function getError(): string;

}
