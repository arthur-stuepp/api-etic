<?php


namespace App\Domain\State;


use App\Domain\ServiceListParams;

interface IStateService
{
    public function read(int $id);

    public function list(ServiceListParams $params);
}