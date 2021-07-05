<?php

declare(strict_types=1);

namespace App\Application\Actions\UserEvent;

use Psr\Http\Message\ResponseInterface as Response;

class UserEventListAction extends UserEventAction
{
    protected function action(): Response
    {
        if (isset($this->args['user'])) {
            if (!isset($this->args['event'])) {
                return $this->respondWithPayload($this->service->getEventsUser((int) $this->args['user']));
            }
            return $this->respondWithPayload($this->service->read((int) $this->args['user'], (int) $this->args['user']));
        }
        return $this->respondWithPayload($this->service->getUsersEvent((int) $this->args['event']));
    }
}
