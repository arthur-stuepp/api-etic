<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\Service\Payload;
use Exception;

class DomainException extends Exception
{
    public function __construct(
        string $message,
        $code = Payload::STATUS_INVALID_ENTITY
    ) {
        parent::__construct($message, $code);
    }
}
