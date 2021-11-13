<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface CrudServiceInterface
{
    public function create(array $data): ServicePayload;

    public function update(int $id, array $data): ServicePayload;

    public function delete(int $id): ServicePayload;

    public function read(int $id): ServicePayload;

    public function list(array $queryParams): ServicePayload;
}
