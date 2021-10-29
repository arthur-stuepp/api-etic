<?php


namespace App\Application\Actions\Address;


use App\Application\Actions\Action;
use App\Domain\Address\IAddressService;

abstract class Address extends Action
{

    protected IAddressService $service;

    public function __construct(IAddressService $service)
    {
        $this->service = $service;
    }

}