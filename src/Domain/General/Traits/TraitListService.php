<?php

declare(strict_types=1);

namespace App\Domain\General\Traits;

use App\Domain\General\ServiceListParams;
use App\Domain\ServicePayload;

trait TraitListService
{

    public function list(array $queryParams): ServicePayload
    {
        $params = new ServiceListParams($this->class, $queryParams);
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
