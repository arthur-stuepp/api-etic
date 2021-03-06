<?php

declare(strict_types=1);


use App\Domain\Event\EventService;
use App\Domain\Event\IEventService;
use App\Domain\School\ISchoolService;
use App\Domain\School\SchoolService;
use DI\ContainerBuilder;
use App\Domain\User\UserService;
use App\Domain\User\IUserService;


use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        IUserService::class => autowire(UserService::class),
        ISchoolService::class => autowire(SchoolService::class),
        IEventService::class => autowire(EventService::class),
    ]);
};
