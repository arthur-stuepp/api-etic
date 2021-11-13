<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\Service\AbstractDomainService;
use App\Infrastructure\Repository\EntityParams;
use App\Domain\Service\ServicePayload;

class AddressService extends AbstractDomainService implements AddressServiceInterface
{

    private AddressRepositoryInterface $repository;

    public function __construct(AddressRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function readState(int $id): ServicePayload
    {
        $state = $this->repository->getStateById($id);
        if ($state === null) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->servicePayload(ServicePayload::STATUS_FOUND, $state);
    }

    public function readCity(int $id): ServicePayload
    {
        $state = $this->repository->getCityById($id);
        if ($state === null) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->servicePayload(ServicePayload::STATUS_FOUND, $state);
    }

    public function listState(array $queryParams): ServicePayload
    {
        $params = new EntityParams(State::class, $queryParams);

        return $this->servicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }

    public function listCity(array $queryParams): ServicePayload
    {
        $params = new EntityParams(City::class, $queryParams);

        return $this->servicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
