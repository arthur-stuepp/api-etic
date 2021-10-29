<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\EntityInterface;

class State extends EntityInterface
{

    protected int $id;

    protected string $code;

    protected string $name;

}
