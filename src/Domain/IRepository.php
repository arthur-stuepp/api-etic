<?php

namespace App\Domain;

interface IRepository
{
    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;

    public function getLastSaveId():int;

    public function getLastError(): string;
}
