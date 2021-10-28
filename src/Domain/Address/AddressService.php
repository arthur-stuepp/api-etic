<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\ApplicationService;
use App\Domain\General\ServiceListParams;
use App\Domain\ServicePayload;

class AddressService extends ApplicationService implements IAddressService
{

    private IAddressRepository $repository;

    public function __construct(IAddressRepository $repository)
    {
        $this->repository = $repository;
    }

    public function readState(int $id): ServicePayload
    {
        $state = $this->repository->getStateById($id);
        if ($state === false) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $state);
    }

    public function readCity(int $id): ServicePayload
    {
        $state = $this->repository->getCityById($id);
        if ($state === false) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $state);
    }

    public function listState(array $queryParams): ServicePayload
    {
        $params = new ServiceListParams(State::class, $queryParams);

        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }

    public function listCity(array $queryParams): ServicePayload
    {
        $params = new ServiceListParams(City::class, $queryParams);

        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
