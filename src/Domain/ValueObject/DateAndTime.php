<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;
use DateTimeZone;
use Exception;

class DateAndTime extends DateTime implements ValueObjectInterface
{
    public function __construct($datetime = 'now', DateTimeZone $timezone = null)
    {
        try {
            parent::__construct($datetime, $timezone);
        } catch (Exception $e) {
            throw new Exception('Formatado de data invaliado. Precisa ser no formtado AAAA-MM-DD HH:MM:SS');
        }
    }

    public function jsonSerialize()
    {
        return $this->format('d-m-y h:m:s');
    }

    public function __toString()
    {
        return $this->format('Y-m-d h:m:s');
    }
}
