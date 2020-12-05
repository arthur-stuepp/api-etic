<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UserDeleteAction extends UserAction
{

    protected function action(): Response
    {
        $id = (int)$this->args['user'];

        $payload = $this->service->delete($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
