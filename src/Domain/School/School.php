<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\AbstractEntity;

class School extends AbstractEntity
{
    protected int $id;
    protected string $name;
}
