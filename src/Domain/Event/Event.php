<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;
use DateTime;

class Event extends Entity
{
    public int $id;

    public string $description;

    public string $capacity;

    public DateTime $startTime;

    public DateTime $endTime;
}
