<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class SaveAction extends CrudAction
{

    protected function action(): Response
    {
        $this->setService();
        $data = $this->getFormData();
        if (count($this->args) === 0) {
            return $this->respondWithPayload($this->service->create($data));
        }
        [$id] = array_values($this->args);
        
        return $this->respondWithPayload($this->service->update((int)$id, $data));

    }
}