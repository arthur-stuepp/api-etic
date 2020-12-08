<?php

declare(strict_types=1);

namespace App\Domain\State;

use App\Domain\Entity;

class State extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);

    }

    public int $id;

    public string $code;

    public string $name;

}
