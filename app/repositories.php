<?php

declare(strict_types=1);

use App\Domain\Event\IEventRepository;
use App\Domain\School\ISchoolRepository;
use App\Domain\User\IUserRepository;
use App\Infrastructure\Repository\EventRepository;
use App\Infrastructure\Repository\SchoolRepository;
use App\Infrastructure\Repository\UserRepository;
use DI\ContainerBuilder;
use function DI\autowire;


return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        ISchoolRepository::class => autowire(SchoolRepository::class),
        IEventRepository::class => autowire(EventRepository::class),
        IUserRepository::class=>autowire(UserRepository::class),
    ]);
};
