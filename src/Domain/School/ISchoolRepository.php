<?php

namespace App\Domain\School;

interface ISchoolRepository
{
    public function create(School $school);

    public function getById(int $id);

    public function delete(int $id);
}
