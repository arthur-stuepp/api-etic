<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Entity;
use App\Domain\General\Interfaces\IUniquiProperties;

class School extends Entity implements IUniquiProperties
{

    public int $id;

    public string $name;

    public function getProperties(): array
    {
        return ['name'];
    }
}
