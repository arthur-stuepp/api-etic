<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Address\City;
use App\Domain\Address\State;
use App\Domain\General\ServiceListParams;

class AddressRepository implements AddressRepositoryInterface
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStateById(int $id): ?State
    {
        $params = new ServiceListParams(State::class);
        $params->setFilters('id', (string)$id)->setLimit(1);

        return $this->list($params)['result'][0] ?? null;
    }

    public function list(ServiceListParams $params): array
    {
        return $this->repository->list($params);
    }

    public function getCityById(int $id): ?City
    {
        $params = new ServiceListParams(City::class);
        $params->setFilters('id', (string)$id)->setLimit(1);
        return $this->list($params)['result'][0] ?? null;
    }
}
