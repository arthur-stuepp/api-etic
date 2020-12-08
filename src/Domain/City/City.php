<?php

declare(strict_types=1);

namespace App\Domain\City;

use App\Domain\Entity;


class City extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public string $name;

    public int $state;
}
