<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\Services\ServicePayload;
use App\Domain\Services\ServiceListParams;;

trait TraitListService
{

    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
