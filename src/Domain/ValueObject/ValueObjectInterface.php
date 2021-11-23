<?php

namespace App\Domain\ValueObject;

use JsonSerializable;

interface ValueObjectInterface extends JsonSerializable
{
    public function __toString();
}
