<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Address\City;
use App\Domain\Address\IAddressRepository;
use App\Domain\Address\State;
use App\Domain\General\ServiceListParams;

class AddressRepository implements IAddressRepository
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $repository)
    {
        $this->repository = $repository;
    }


    public function getStateById(int $id)
    {
        $params = new ServiceListParams(State::class);
        $params->setFilters('id', (string)$id)->setLimit(1);
        return $this->list($params)['result'][0] ?? false;
    }

    public function list(ServiceListParams $params): array
    {
        return $this->repository->list($params);

    }

    public function getCityById(int $id)
    {
        $params = new ServiceListParams(City::class);
        $params->setFilters('id', (string)$id)->setLimit(1);
        return $this->list($params)['result'][0] ?? false;
    }
}
