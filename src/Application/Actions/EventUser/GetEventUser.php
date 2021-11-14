<?php

declare(strict_types=1);

namespace App\Application\Actions\EventUser;

use Psr\Http\Message\ResponseInterface as Response;

class GetEventUser extends AbstractEventUserAction
{
    protected function action(): Response
    {
        if (isset($this->args['user'])) {
            [$eventId, $userId] = array_values($this->args);
            return $this->respondWithPayload($this->service->readUser((int)$eventId, (int)$userId));
        }
        die();
    }
}
