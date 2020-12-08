<?php


namespace App\Domain\City;


use App\Domain\ServiceListParams;

interface ICityService
{
    public function read(int $id);

    public function list(ServiceListParams $params);
}