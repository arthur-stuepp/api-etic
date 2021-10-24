<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Domain\Services\ServicePayload;
use App\Domain\Services\ServiceListParams;;

trait TraitListService
{

    public function list(ServiceListParams $params): ServicePayload
    {
        if (isset($this->validation)) {
            if (method_exists($this->validation, 'hasPermissionToList')) {
                if (!$this->validation->hasPermissionToList()) {
                    return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
                }
            }
        }
    
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params));
    }
}
