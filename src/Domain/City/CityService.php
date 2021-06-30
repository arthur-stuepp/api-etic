<?php


declare(strict_types=1);

namespace App\Domain\City;

use App\Domain\ServicePayload;
use App\Domain\ServiceListParams;
use App\Domain\ApplicationService;
use App\Domain\Traits\TraitReadService;


class CityService extends ApplicationService implements ICityService
{

    private ICityRepository $repository;

    public function __construct(ICityRepository $repository)
    {

        $this->repository = $repository;
    }

    use TraitReadService;

    public function list(ServiceListParams $params): ServicePayload
    {
    
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
