<?php

namespace App\Domain;

use App\Infrastructure\Repository\EntityParams;

interface RepositoryInterface
{
    public function getById(int $id): ?AbstractEntity;

    public function list(EntityParams $params): array;

    public function getError(): string;

    public function delete(int $id): bool;
}
