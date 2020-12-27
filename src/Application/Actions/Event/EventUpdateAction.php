<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use Psr\Http\Message\ResponseInterface as Response;

class EventUpdateAction extends EventAction
{
    protected function action(): Response
    {
        $data = $this->getFormData();
        $id = $this->args['event'];

        $payload = $this->service->update($id, $data);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
