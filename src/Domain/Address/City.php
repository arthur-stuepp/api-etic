<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\EntityInterface;

class City extends EntityInterface
{

    protected int $id;

    protected string $name;

    protected State $state;
}
