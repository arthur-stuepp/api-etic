<?php

declare(strict_types=1);

use App\Domain\Address\AddressService;
use App\Domain\Address\AddressServiceInterface;
use App\Domain\General\Interfaces\AuthServiceInterface;
use App\Domain\User\UserService;
use DI\ContainerBuilder;
use function DI\autowire;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        AddressServiceInterface::class => autowire(AddressService::class),
        AuthServiceInterface::class => autowire(UserService::class),
    ]);
};
