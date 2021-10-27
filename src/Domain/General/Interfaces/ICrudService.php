<?php

declare(strict_types=1);

namespace App\Domain\General\Interfaces;

use App\Domain\General\ServiceListParams;
use App\Domain\ServicePayload;

interface ICrudService
{
    public function create(array $data): ServicePayload;

    public function update(int $id, array $data): ServicePayload;

    public function delete(int $id): ServicePayload;

    public function read(int $id): ServicePayload;

    public function list(array $queryParams): ServicePayload;
}
