<?php

declare(strict_types=1);

namespace App\Domain\General\Factory;

use App\Domain\AbstractEntity;
use App\Domain\Address\City;
use App\Domain\Address\State;
use App\Domain\Event\Event;
use App\Domain\School\School;
use App\Domain\User\User;

class EntityFactory
{
    private static array $entities = [
        'user' => User::class,
        'event' => Event::class,
        'school' => School::class,
        'city' => City::class,
        'state' => State::class,
    ];


    public static function entityExist(string $field): bool
    {
        return isset(self::$entities[$field]);
    }

    public static function getEntity(string $field, array $value): AbstractEntity
    {
        return new self::$entities[$field]($value);
    }

}