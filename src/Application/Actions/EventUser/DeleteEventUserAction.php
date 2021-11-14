<?php

declare(strict_types=1);

namespace App\Application\Actions\EventUser;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteEventUserAction extends AbstractEventUserAction
{
    protected function action(): Response
    {
        [$eventId, $userId] = array_values($this->args);

        return $this->respondWithPayload($this->service->removeUser((int)$eventId, (int)$userId));
    }
}
