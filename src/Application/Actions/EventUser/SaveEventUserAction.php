<?php

declare(strict_types=1);

namespace App\Application\Actions\EventUser;

use Psr\Http\Message\ResponseInterface as Response;

class SaveEventUserAction extends AbstractEventUserAction
{
    protected function action(): Response
    {
        $data = $this->getFormData();
        $data['event'] = $this->args['event'];
        $userId = (int)$this->args['user'];
        if ($this->request->getMethod() === 'POST') {
            return $this->respondWithPayload($this->service->addUser($userId, $data));
        }
        return $this->respondWithPayload($this->service->updateUser($userId, $data));
    }
}
