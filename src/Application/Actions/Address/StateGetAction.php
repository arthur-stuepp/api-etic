<?php

declare(strict_types=1);

namespace App\Application\Actions\Address;

use Psr\Http\Message\ResponseInterface as Response;

class StateGetAction extends Address
{
    protected function action(): Response
    {
        $queryParams = $this->request->getQueryParams();
        if (count($this->args) === 0) {
            return $this->respondWithPayload($this->service->listState($queryParams));
        }
        [$id] = array_values($this->args);

        return $this->respondWithPayload($this->service->readState((int)$id));

    }
}
