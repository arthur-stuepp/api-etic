<?php

namespace App\Domain\School;

use App\Domain\IRepository;

interface ISchoolRepository extends IRepository
{
    public function save(School $school);

    public function getById(int $id): School|false;

    public function getByName(string $name): School|false;
}
