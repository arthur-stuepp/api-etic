<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UserReadAction extends UserAction
{
    protected function action(): Response
    {
        $id = (int)$this->args['id'];

        $payload = $this->service->read($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
