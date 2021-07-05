<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\User\User;
use App\Domain\Event\Event;
use App\Domain\ServiceListParams;
use App\Domain\UserEvent\UserEvent;


class ParamsFactory
{
    public static function getParams(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }

    public static function User(): ServiceListParams
    {
        return new ServiceListParams(User::class);
    }
    public static function UserId(int $id): ServiceListParams
    {
        $params = new ServiceListParams(User::class);
        $params->setLimit(1);
        $params->setFilters('id', (string)$id);
        return $params;
    }
    public static function EventId(int $id): ServiceListParams
    {
        $params = new ServiceListParams(Event::class);
        $params->setLimit(1);
        $params->setFilters('id', (string)$id);
        return $params;
    }

    public static function Event(): ServiceListParams
    {
        return new ServiceListParams(Event::class);
    }
    public static function UserEvent(): ServiceListParams
    {
        return new ServiceListParams(UserEvent::class);
    }
}
