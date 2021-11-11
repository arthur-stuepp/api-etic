<?php

declare(strict_types=1);

namespace App\Domain\General\Traits;

use App\Domain\General\ServiceListParams;
use App\Domain\ServicePayload;

trait TraitReadService
{

    public function read(int $id): ServicePayload
    {
        $params = new ServiceListParams($this->class);
        $params->setFilters('id', (string)($id))->setLimit(1);
        $entity = $this->repository->list($params)['result'] ?? false;
        if (!$entity) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }

        return $this->servicePayload(ServicePayload::STATUS_FOUND, $entity);
    }
}
