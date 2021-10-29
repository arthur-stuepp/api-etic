<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends CrudAction
{

    protected function action(): Response
    {
        $this->setService();
        [$id] = array_values($this->args);

        return $this->respondWithPayload($this->service->delete((int)$id));

    }
}