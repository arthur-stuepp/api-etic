<?php


namespace App\Application\Actions\City;


use App\Domain\City\City;
use App\Domain\ServiceListParams;
use Psr\Http\Message\ResponseInterface as Response;

class CityListAction extends CityAction
{
    protected function action(): Response
    {
        $params = new ServiceListParams(City::class, $this->args);
        $payload = $this->service->list($params);

        return $this->respondWithData($payload->getResult())->withStatus($payload->getStatus());
    }
}