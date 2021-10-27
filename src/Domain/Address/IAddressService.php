<?php


namespace App\Domain\Address;


use App\Domain\General\ServiceListParams;


interface IAddressService
{
    public function readState(int $id);

    public function readCity(int $id);

    public function listState(array $queryParams);

    public function listCity(array $queryParams);
}