<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use function DI\autowire;
use App\Domain\Auth\AuthService;
use App\Domain\City\CityService;
use App\Domain\User\UserService;
use App\Domain\Auth\IAuthService;
use App\Domain\City\ICityService;
use App\Domain\User\IUserService;
use App\Domain\Event\EventService;
use App\Domain\State\StateService;
use App\Domain\Event\IEventService;
use App\Domain\State\IStateService;
use App\Domain\School\SchoolService;
use App\Domain\School\ISchoolService;
use App\Domain\UserEvent\UserEventService;
use App\Domain\UserEvent\IUserEventService;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        IUserService::class => autowire(UserService::class),
        ISchoolService::class => autowire(SchoolService::class),
        IEventService::class => autowire(EventService::class),
        IUserEventService::class => autowire(UserEventService::class),
        ICityService::class => autowire(CityService::class),
        IStateService::class => autowire(StateService::class),
        IAuthService::class => autowire(AuthService::class),
    ]);
};
