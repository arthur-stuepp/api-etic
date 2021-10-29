<?php

namespace App\Domain\School;

use App\Domain\General\Interfaces\RepositoryInterface;

interface SchoolRepositoryInterface extends RepositoryInterface
{
    public function save(School $school): bool;

    public function getById(int $id);

}
