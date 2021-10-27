<?php

declare(strict_types=1);

use App\Domain\Address\AddressService;
use App\Domain\Address\IAddressService;
use App\Domain\General\Interfaces\IAuthService;
use App\Domain\User\UserService;
use DI\ContainerBuilder;
use function DI\autowire;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        IAddressService::class => autowire(AddressService::class),
        IAuthService::class => autowire(UserService::class),
    ]);
};
