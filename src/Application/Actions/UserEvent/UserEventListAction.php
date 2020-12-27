<?php


namespace App\Application\Actions\UserEvent;


use Psr\Http\Message\ResponseInterface as Response;

class UserEventListAction extends UserEventAction
{
    protected function action(): Response
    {

        $idUser = (int)$this->args['user'] ??= null;
        $idEvent = (int)$this->args['event'] ??= null;

        $payload = $this->service->list($idUser, $idEvent);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());

    }

}