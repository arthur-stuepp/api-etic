<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\Entity;

class State extends Entity
{

    protected int $id;

    protected string $code;

    protected string $name;

}
