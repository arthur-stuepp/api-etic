<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;
use App\Domain\General\Model\DateTimeModel;
use App\Domain\User\User;

class Event extends Entity
{
    public const TYPE_EVENT = 1;
    public const TYPE_GAME = 2;
    public const TYPE_HACKATHON = 3;

    public int $id;

    public string $name;

    public int $type = self::TYPE_EVENT;

    public string $description;

    public int $capacity;

    public DateTimeModel $startTime;

    public DateTimeModel $endTime;

    /**
     * @var EventUser[]
     */
    private array $users;
}
