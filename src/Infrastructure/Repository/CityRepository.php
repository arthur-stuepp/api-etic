<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\City\City;
use App\Domain\City\ICityRepository;
use App\Domain\Services\ServiceListParams;

class CityRepository implements ICityRepository
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getById(int $id)
    {
        $params = new ServiceListParams(City::class);
        $params->setFilters('id', (string)$id)->setLimit(1);
        return $this->list($params)['result'][0] ?? false;
    }

    public function list(ServiceListParams $params): array
    {
      
        return $this->repository->list($params);
   
    }


}
