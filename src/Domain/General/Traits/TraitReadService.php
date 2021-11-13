<?php

declare(strict_types=1);

namespace App\Domain\General\Traits;

use App\Domain\ServicePayload;

trait TraitReadService
{

    public function read(int $id): ServicePayload
    {
        $entity = $this->repository->getById($id);
        if ($entity === null) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }

        return $this->servicePayload(ServicePayload::STATUS_FOUND, $entity);
    }
}
