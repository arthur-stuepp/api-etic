<?php


namespace App\Application\Actions\Address;


use App\Application\Actions\Action;
use App\Domain\Address\AddressServiceInterface;

abstract class Address extends Action
{

    protected AddressServiceInterface $service;

    public function __construct(AddressServiceInterface $service)
    {
        $this->service = $service;
    }

}