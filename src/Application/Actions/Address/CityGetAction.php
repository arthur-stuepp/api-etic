<?php


namespace App\Application\Actions\Address;


use Psr\Http\Message\ResponseInterface as Response;

class CityGetAction extends Address
{
    protected function action(): Response
    {
        $queryParams = $this->request->getQueryParams();
  
        if (count($this->args) === 1 ){
            [$state] = array_values($this->args);
            $queryParams['state'] = $state;
            return $this->respondWithPayload($this->service->listCity($queryParams));
        }
        [,$id] = array_values($this->args);
        

        return $this->respondWithPayload($this->service->readCity($id));

    }
}