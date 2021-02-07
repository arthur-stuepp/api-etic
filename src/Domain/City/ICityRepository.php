<?php

namespace App\Domain\City;


use App\Domain\ServiceListParams;

interface ICityRepository 
{
    public function getById(int $id);

    public function list(ServiceListParams $params);
}
