<?php

declare(strict_types=1);

namespace App\Application\Actions\School;

use Psr\Http\Message\ResponseInterface as Response;

class SchoolUpdateAction extends SchoolAction
{

    protected function action(): Response
    {
        $data = $this->getFormData();
        $id = (int)$this->args['id'];
        $payload = $this->service->update($id, $data);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}
