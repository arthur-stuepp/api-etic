<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends AuthAction
{
    protected function action(): Response
    {
        return $this->respondWithPayload($this->service->auth($this->getFormData()));
    }
}
