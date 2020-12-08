<?php

namespace App\Domain\State;


use App\Domain\ServiceListParams;

interface IStateRepository
{
    /**
     * @param int $id
     * @return false|State
     */
    public function getById(int $id);

    public function list(ServiceListParams $params);
}
