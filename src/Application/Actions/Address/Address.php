<?php

declare(strict_types=1);

namespace App\Application\Actions\Address;

use App\Application\Actions\Action;
use App\Domain\Address\AddressServiceInterface;
use Psr\Log\LoggerInterface;

abstract class Address extends Action
{

    protected AddressServiceInterface $service;

    public function __construct(AddressServiceInterface $service, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->service = $service;
    }
}
