<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UserUpdateAction extends UserAction
{

    protected function action(): Response
    {
        $data = $this->getFormData();
        $id = $this->args['id'];

        $payload = $this->service->update($id, $data);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
