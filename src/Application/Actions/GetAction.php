<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class GetAction extends CrudAction
{

    protected function action(): Response
    {
        $queryParams = $this->request->getQueryParams();
        $this->setService();
        if (count($this->args) === 0) {
            return $this->respondWithPayload($this->service->list($queryParams));
        }
        [$id] = array_values($this->args);

        return $this->respondWithPayload($this->service->read((int)$id));

    }
}