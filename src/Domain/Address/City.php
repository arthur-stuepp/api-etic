<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\Entity;

class City extends Entity
{

    protected int $id;

    protected string $name;

    protected State $state;
}
