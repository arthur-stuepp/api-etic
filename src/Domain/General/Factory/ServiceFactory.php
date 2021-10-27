<?php

declare(strict_types=1);

namespace App\Domain\General\Factory;

use App\Domain\Event\EventService;
use App\Domain\General\Interfaces\ICrudService;
use App\Domain\School\SchoolService;
use App\Domain\User\UserService;
use DI\Container;

class ServiceFactory
{
    private Container $container;
    private array $services = [
        'users' => UserService::class,
        'events' => EventService::class,
        'schools' => SchoolService::class,
    ];

    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function getService(string $pattern): ICrudService
    {
        return $this->container->get($this->services[$pattern]);
    }

}