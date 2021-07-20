<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;
use DateTime;

class Event extends Entity
{


    public const TYPE_EVENT = 1;
    public const TYPE_GAME = 2;
    public const TYPE_HACKATHON = 3;
   
    public int $id;

    public string $name;

    public int $type;

    public string $description;

    public string $capacity;

    public DateTime $startTime;

    public DateTime $endTime;
}
