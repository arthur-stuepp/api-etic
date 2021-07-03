<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Entity;

class School extends Entity
{

    public int $id;

    public string $name;
}
