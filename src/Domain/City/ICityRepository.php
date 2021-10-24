<?php

namespace App\Domain\City;


use App\Domain\IRepository;
use App\Domain\Services\ServiceListParams;;

interface ICityRepository 
{
    /*
    *@return City|false;
    */
    public function getById(int $id);

    public function list(ServiceListParams $params): array;

}
