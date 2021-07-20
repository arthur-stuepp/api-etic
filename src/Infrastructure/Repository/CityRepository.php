<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\City\City;
use App\Domain\City\ICityRepository;

class CityRepository extends MysqlRepository implements ICityRepository
{
    protected function getClass(): string
    {
        return City::class;
    }
}
