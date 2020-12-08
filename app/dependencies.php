<?php

declare(strict_types=1);


use App\Domain\City\CityService;
use App\Domain\City\ICityService;
use App\Domain\Event\EventService;
use App\Domain\Event\IEventService;
use App\Domain\School\ISchoolService;
use App\Domain\School\SchoolService;
use App\Domain\User\IUserService;
use App\Domain\User\UserService;
use App\Domain\UserEvent\IUserEventService;
use App\Domain\UserEvent\UserEventService;
use DI\ContainerBuilder;
use function DI\autowire;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        IUserService::class => autowire(UserService::class),
        ISchoolService::class => autowire(SchoolService::class),
        IEventService::class => autowire(EventService::class),
        IUserEventService::class => autowire(UserEventService::class),
        ICityService::class => autowire(CityService::class),
    ]);
};
