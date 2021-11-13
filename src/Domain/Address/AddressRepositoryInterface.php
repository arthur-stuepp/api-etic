<?php

namespace App\Domain\Address;

use App\Domain\General\ServiceListParams;

interface AddressRepositoryInterface
{
    public function getStateById(int $id): ?State;

    public function getCityById(int $id): ?City;

    public function list(ServiceListParams $params);
}
