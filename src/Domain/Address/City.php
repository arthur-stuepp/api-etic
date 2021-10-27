<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\Entity;

class City extends Entity
{

    public int $id;

    public string $name;

    public State $state;
}
