<?php

declare(strict_types=1);

use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\Event\EventRepositoryInterface;
use App\Domain\School\SchoolRepositoryInterface;
use App\Domain\User\UserRepositoryInterfaceInterface;
use App\Infrastructure\Repository\AddressRepository;
use App\Infrastructure\Repository\EventRepository;
use App\Infrastructure\Repository\SchoolRepository;
use App\Infrastructure\Repository\UserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        SchoolRepositoryInterface::class => autowire(SchoolRepository::class),
        EventRepositoryInterface::class => autowire(EventRepository::class),
        UserRepositoryInterfaceInterface::class => autowire(UserRepository::class),
        AddressRepositoryInterface::class => autowire(AddressRepository::class),
    ]);
};
