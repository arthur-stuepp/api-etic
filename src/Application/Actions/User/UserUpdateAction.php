<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UserUpdateAction extends UserAction
{

    protected function action(): Response
    {

        return $this->respondWithPayload($this->service->update((int)$this->args['user'], $this->getFormData()));
    }
}
