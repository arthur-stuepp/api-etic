<?php


namespace App\Application\Actions\City;


use Psr\Http\Message\ResponseInterface as Response;

class CityReadAction extends CityAction
{
    protected function action(): Response
    {
        $id = (int)$this->args['id'];

        $payload = $this->service->read($id);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}