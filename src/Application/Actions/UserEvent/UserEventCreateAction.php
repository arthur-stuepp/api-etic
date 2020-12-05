<?php


namespace App\Application\Actions\UserEvent;


use Psr\Http\Message\ResponseInterface as Response;

class UserEventCreateAction extends UserEventAction
{
    protected function action(): Response
    {
        {
            $idUser = (int)$this->args['user'];
            $idEvent = (int)$this->args['event'];

            $payload = $this->service->add($idUser, $idEvent);

            return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
        }
    }

}