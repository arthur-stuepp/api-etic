<?php


declare(strict_types=1);

namespace App\Domain\Model;

use DateTime;
use JsonSerializable;

class DateTimeModel extends DateTime implements JsonSerializable
{

    public function jsonSerialize()
    {
        return  $this->format('d-m-y h:m:s');
    }

    public function __toString()
    {
        return  $this->format('d-m-y h:m:s');
    }
}
