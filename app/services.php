<?php

declare(strict_types=1);

use App\Domain\Address\AddressService;
use App\Domain\Address\IAddressService;
use App\Domain\General\Interfaces\AuthServiceInterface;
use App\Domain\User\UserServiceInterfaceInterface;
use DI\ContainerBuilder;
use function DI\autowire;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        IAddressService::class => autowire(AddressService::class),
        AuthServiceInterface::class => autowire(UserServiceInterfaceInterface::class),
    ]);
};
