<?php


namespace App\Application\Actions\State;


use Psr\Http\Message\ResponseInterface as Response;

class StateReadAction extends StateAction
{
    protected function action(): Response
    {
        $id = (int)$this->args['id'];

        $payload = $this->service->read($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}