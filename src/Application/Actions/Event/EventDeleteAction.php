<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class EventDeleteAction extends EventAction
{

    protected function action(): Response
    {
        $id = (int)$this->args['event'];

        $payload = $this->service->delete($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
