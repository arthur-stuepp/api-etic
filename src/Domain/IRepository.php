<?php

namespace App\Domain;

use App\Domain\Services\ServiceListParams;

interface IRepository
{
    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;
    
}
