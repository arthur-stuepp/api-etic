<?php

declare(strict_types=1);

namespace App\Domain\State;

use App\Domain\ApplicationService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;


class StateService extends ApplicationService implements IStateService
{

    use TraitReadService;
    use TraitListService;
    private IStateRepository $repository;
    public function __construct(IStateRepository $repository)
    {

        $this->repository = $repository;
    }
}
