<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class EventReadAction extends EventAction
{
    protected function action(): Response
    {
        $id = (int)$this->args['event'];

        return $this->respondWithPayload($this->service->read($id));
    }
}
