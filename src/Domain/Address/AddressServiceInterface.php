<?php

namespace App\Domain\Address;

interface AddressServiceInterface
{
    public function readState(int $id);

    public function readCity(int $id);

    public function listState(array $queryParams);

    public function listCity(array $queryParams);
}
