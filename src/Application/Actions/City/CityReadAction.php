<?php


namespace App\Application\Actions\City;


use Psr\Http\Message\ResponseInterface as Response;

class CityReadAction extends CityAction
{
    protected function action(): Response
    {
        return $this->respondWithPayload($this->service->read((int)$this->args['id']));
    }
}
