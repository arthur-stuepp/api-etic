<?php

namespace App\Domain\Address;

use App\Infrastructure\Repository\EntityParams;

interface AddressRepositoryInterface
{
    public function getStateById(int $id): ?State;

    public function getCityById(int $id): ?City;

    public function list(EntityParams $params);
}
