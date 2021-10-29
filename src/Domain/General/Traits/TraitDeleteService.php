<?php

declare(strict_types=1);

namespace App\Domain\General\Traits;

use App\Domain\ServicePayload;

trait TraitDeleteService
{

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Deletado com sucesso']);
            } else {
                if (strpos($this->repository->getError(), '1451 Cannot delete or update a parent row') !== false) {
                    return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['message' => 'Não é possivel deletar esse registro porque está associado a outros registros.', 'description' => $this->repository->getError()]);
                }
                return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => 'Não foi possivel deletar esse registro', 'description' => $this->repository->getError()]);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
    }
}
