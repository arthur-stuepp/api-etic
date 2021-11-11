<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\AbstractEntity;

class State extends AbstractEntity
{

    protected int $id;

    protected string $code;

    protected string $name;

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }


}
