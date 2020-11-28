<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UserCreateAction extends UserAction
{

    protected function action(): Response
    {
        $data = $this->getFormData();

        $payload = $this->service->Create($data);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
