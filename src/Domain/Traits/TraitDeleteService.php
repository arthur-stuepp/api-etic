<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\ServicePayload;

trait TraitDeleteService
{

    public function delete(int $id): ServicePayload
    {
        if (isset($this->validation)) {
            if (method_exists($this->validation, 'canDelete')) {
                if (!$this->validation->canDelete()) {
                    return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
                }
            }
        }
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Deletado com sucesso']);
            } else {
                if (strpos($this->repository->getLastError(), '1451 Cannot delete or update a parent row') !== false) {
                    return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['message' => 'Não é possivel deletar esse registro porque está associado a outros registros.', 'description' => $this->repository->getLastError()]);
                }
                return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => 'Não foi possivel deletar esse registro', 'description' => $this->repository->getLastError()]);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
    }
}
