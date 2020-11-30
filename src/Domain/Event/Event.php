<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;
use DateTime;

class Event extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public int $id;

    public string $description;

    public string $capacity;

    public DateTime $startTime;

    public DateTime $endTime;

    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();
        if (isset($json['startTime'])) {
            $json['startTime'] = $this->startTime->format('d-m-y h:m:s');
        }
        if (isset($json['startTime'])) {
            $json['endTime'] = $this->endTime->format('d-m-y h:m:s');
        }


        return $json;
    }
}
