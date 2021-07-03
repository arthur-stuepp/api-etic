<?php

declare(strict_types=1);

namespace App\Domain\City;

use App\Domain\ServicePayload;
use App\Domain\ServiceListParams;
use App\Domain\ApplicationService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;

class CityService extends ApplicationService implements ICityService
{
    private ICityRepository $repository;

    use TraitReadService;
    use TraitListService;

    public function __construct(ICityRepository $repository)
    {

        $this->repository = $repository;
    }
}
