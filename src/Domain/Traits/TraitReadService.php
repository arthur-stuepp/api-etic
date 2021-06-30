<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\ServicePayload;

trait TraitReadService
{

    public function read(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND,  $this->repository->getById($id));
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
    }
}
