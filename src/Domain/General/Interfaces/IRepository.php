<?php

namespace App\Domain\General\Interfaces;

use App\Domain\General\ServiceListParams;

interface IRepository{
    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;

    public function getError(): string;

}
