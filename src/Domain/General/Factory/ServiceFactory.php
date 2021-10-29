<?php

declare(strict_types=1);

namespace App\Domain\General\Factory;

use App\Domain\Event\EventServiceInterface;
use App\Domain\General\Interfaces\CrudServiceInterface;
use App\Domain\School\SchoolServiceInterface;
use App\Domain\User\UserServiceInterfaceInterface;
use DI\Container;

class ServiceFactory
{
    private Container $container;
    private array $services = [
        'users' => UserServiceInterfaceInterface::class,
        'events' => EventServiceInterface::class,
        'schools' => SchoolServiceInterface::class,
    ];

    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function getService(string $pattern): CrudServiceInterface
    {
        return $this->container->get($this->services[$pattern]);
    }

}