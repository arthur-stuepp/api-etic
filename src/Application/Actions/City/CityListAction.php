<?php


namespace App\Application\Actions\City;


use App\Domain\City\City;
use App\Domain\ServiceListParams;
use Psr\Http\Message\ResponseInterface as Response;

class CityListAction extends CityAction
{
    protected function action(): Response
    {
        $params = new ServiceListParams(City::class, $this->request->getQueryParams());
        
        return $this->respondWithPayload($this->service->list($params));
    }
}
