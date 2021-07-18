<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\ServicePayload;

trait TraitReadService
{

    public function read(int $id): ServicePayload
    {
        if (isset($this->validation)) {
            if(method_exists($this->validation,'canRead')){
                if (!$this->validation->canRead($id)) {
                    return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
                }
            }
    
        }

        $entity = $this->repository->getById($id);
        if (!$entity) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }

        return $this->ServicePayload(ServicePayload::STATUS_FOUND,  $entity);
    }
}
