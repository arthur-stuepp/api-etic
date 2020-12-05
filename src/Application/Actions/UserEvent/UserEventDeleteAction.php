<?php


namespace App\Application\Actions\UserEvent;


use Psr\Http\Message\ResponseInterface as Response;

class UserEventDeleteAction extends UserEventAction
{
    protected function action(): Response
    {
        {
            $idUser = (int)$this->args['user'];
            $idEvent = (int)$this->args['event'];

            $payload = $this->service->remove($idUser, $idEvent);

            return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
        }
    }

}