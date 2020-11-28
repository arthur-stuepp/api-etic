<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\School\ISchoolRepository;
use App\Domain\School\School;

class SchoolRepository extends MysqlRepository implements ISchoolRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'schools';
        $this->class = 'School';
    }

    public function create(School $school)
    {
        return parent::insert($school->jsonSerialize());
    }

    public function getByName(string $name)
    {
        return parent::getByField('name', $name);
    }

    public function getById(int $id)
    {
        return parent::getByField('id', $id);
    }

    public function delete(int $id)
    {
        return parent::delete($id);
    }
}
