<?php


namespace App\Domain\State;


use App\Domain\Services\ServiceListParams;;

interface IStateService
{
    public function read(int $id);

    public function list(ServiceListParams $params);
}