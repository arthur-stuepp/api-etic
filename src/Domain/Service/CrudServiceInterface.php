<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface CrudServiceInterface
{
    public function create(array $data): Payload;

    public function update(int $id, array $data): Payload;

    public function delete(int $id): Payload;

    public function read(int $id): Payload;

    public function list(array $queryParams): Payload;
}
