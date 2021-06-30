<?php

namespace App\Domain\City;


use App\Domain\IRepository;
use App\Domain\ServiceListParams;

interface ICityRepository extends IRepository
{
    public function getById(int $id);

}
