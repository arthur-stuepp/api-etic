<?php


declare(strict_types=1);

namespace App\Domain\City;

use App\Domain\ApplicationService;
use App\Domain\ServiceListParams;
use App\Domain\ServicePayload;


class CityService extends ApplicationService implements ICityService
{

    private ICityRepository $repository;

    public function __construct(ICityRepository $repository)
    {

        $this->repository = $repository;
    }


    public function read(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['city' => $this->repository->getById($id)]);
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['city' => 'Cidade não encontrada']);
    }


    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['users' => 'users']);
    }


}