<?php

namespace App\Domain\School;

use App\Domain\IRepository;

interface ISchoolRepository extends IRepository
{
    public function save(School $school): bool;

    public function getById(int $id);

}
