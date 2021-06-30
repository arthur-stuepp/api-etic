<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\School\ISchoolRepository;
use App\Domain\School\School;

class SchoolRepository extends MysqlRepository implements ISchoolRepository
{
    public function getClass(): string
    {
        return School::class;
    }
}
