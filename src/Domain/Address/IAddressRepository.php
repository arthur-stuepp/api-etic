<?php

namespace App\Domain\Address;

use App\Domain\General\ServiceListParams;

interface IAddressRepository
{
    /**
     * @param int $id
     * @return false|State
     */
    public function getStateById(int $id);

    /**
     * @param int $id
     * @return false|City
     */
    public function getCityById(int $id);

    public function list(ServiceListParams $params);
}
