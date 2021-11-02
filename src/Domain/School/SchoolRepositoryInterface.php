<?php

namespace App\Domain\School;

use App\Domain\General\ServiceListParams;

interface SchoolRepositoryInterface
{
    public function save(School $school): bool;

    public function getById(int $id);

    public function list(ServiceListParams $params): array;

    public function delete(int $id): bool;

    public function getError(): string;
}
