<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use function DI\autowire;
use App\Domain\User\UserService;
use App\Domain\User\IUserService;
use App\Domain\City\ICityRepository;
use App\Domain\User\IUserRepository;
use App\Domain\Event\IEventRepository;
use App\Domain\State\IStateRepository;
use App\Domain\School\ISchoolRepository;
use App\Domain\UserEvent\IUserEventRepository;
use App\Infrastructure\Repository\CityRepository;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Repository\EventRepository;
use App\Infrastructure\Repository\StateRepository;
use App\Infrastructure\Repository\SchoolRepository;
use App\Infrastructure\Repository\UserEventRepository;


return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        ISchoolRepository::class => autowire(SchoolRepository::class),
        IEventRepository::class => autowire(EventRepository::class),
        IUserRepository::class => autowire(UserRepository::class),
        IUserService::class => autowire(UserService::class),
        IUserEventRepository::class => autowire(UserEventRepository::class),
        ICityRepository::class => autowire(CityRepository::class),
        IStateRepository::class => autowire(StateRepository::class),
    ]);
};
