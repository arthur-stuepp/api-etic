<?php

declare(strict_types=1);

namespace App\Domain\DomainException;

use App\Domain\ServicePayload;
use Exception;
use Throwable;

abstract class DomainException extends Exception
{
    public function __construct($message, $code = ServicePayload::STATUS_INVALID_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}