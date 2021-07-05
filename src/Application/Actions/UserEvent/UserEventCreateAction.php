<?php

declare(strict_types=1);

namespace App\Application\Actions\UserEvent;

use Psr\Http\Message\ResponseInterface as Response;

class UserEventCreateAction extends UserEventAction
{
    protected function action(): Response
    {
        $idUser = (int)$this->args['user'];
        $idEvent = (int)$this->args['event'];

        return $this->respondWithPayload($this->service->create($idUser, $idEvent, $this->getFormData()));
    }
}
