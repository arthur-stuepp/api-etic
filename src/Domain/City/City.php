<?php

declare(strict_types=1);

namespace App\Domain\City;

use App\Domain\Entity;
use App\Domain\State\State;


class City extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public int $id;

    public string $name;

    public State $state;
}
