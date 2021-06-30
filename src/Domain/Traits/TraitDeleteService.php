<?php 

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\ServicePayload;

Trait TraitDeleteService{

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Deletado com sucesso']);
            } else {
                return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['message' => 'Registro não pode ser deletado']);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
    }
}