<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Entity;
use App\Domain\IHasUniquiProperties;

class School extends Entity implements IHasUniquiProperties
{

    public int $id;

    public string $name;

    public function getFields(): array
    {
        return ['name'];
    }
}
