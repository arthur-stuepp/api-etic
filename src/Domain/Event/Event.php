<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity;

class Event extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
    public int $id;

    public string $description;

    public string $capacity;
}
