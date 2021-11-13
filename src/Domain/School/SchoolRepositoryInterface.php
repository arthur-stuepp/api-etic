<?php

namespace App\Domain\School;

use App\Domain\RepositoryInterface;

interface SchoolRepositoryInterface extends RepositoryInterface
{
    public function save(School $school): bool;

    public function getById(int $id): ?School;
}
