<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\City\City;
use App\Domain\City\ICityRepository;
use App\Domain\ServiceListParams;

class CityRepository extends MysqlRepository implements ICityRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cities';
        $this->class = City::class;
    }


    public function getById(int $id)
    {
        return $this->getByField('id', $id);
    }


    public function list(ServiceListParams $params)
    {
        // TODO: Implement list() method.
    }
}
