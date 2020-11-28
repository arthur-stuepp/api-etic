<?php

namespace App\Domain\City;

use App\Domain\Entity;

declare(strict_types=1);

class City extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public string $name;

    public int $state;
}
