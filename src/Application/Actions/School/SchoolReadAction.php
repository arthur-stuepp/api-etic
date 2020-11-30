<?php

declare(strict_types=1);

namespace App\Application\Actions\School;

use Psr\Http\Message\ResponseInterface as Response;

class SchoolReadAction extends SchoolAction
{
    protected function action(): Response
    {
        $id = (int)$this->args['id'];
        $payload = $this->service->read($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
