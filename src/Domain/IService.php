<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\ServicePayload;

interface IService
{
    public function Create(array $data): ServicePayload;

    public function update(int $id, array $data): ServicePayload;

    public function Delete(int $id): ServicePayload;

    public function read(int $id): ServicePayload;

    public function list(ServiceListParams $params): ServicePayload;
}
