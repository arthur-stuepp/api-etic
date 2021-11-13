<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\Service\ServicePayload;
use Exception;

class DomainException extends Exception
{
    public function __construct(
        string $message,
        $code = ServicePayload::STATUS_INVALID_ENTITY
    ) {
        parent::__construct($message, $code);
    }
}
