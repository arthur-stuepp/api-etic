<?php

namespace App\Domain\State;


use App\Domain\Service\ServiceListParams;

interface IStateRepository
{
    public function getById(int $id);

    public function list(ServiceListParams $params);
}
