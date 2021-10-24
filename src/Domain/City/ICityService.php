<?php

namespace App\Domain\City;

use App\Domain\Services\ServiceListParams;;
interface ICityService
{
    public function read(int $id);

    public function list(ServiceListParams $params);
}