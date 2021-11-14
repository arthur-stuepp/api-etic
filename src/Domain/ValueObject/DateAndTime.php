<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;
use JsonSerializable;

class DateAndTime extends DateTime implements JsonSerializable
{

    public function jsonSerialize()
    {
        return $this->format('d-m-y h:m:s');
    }

    public function __toString()
    {
        return $this->format('Y-m-d h:m:s');
    }
}
