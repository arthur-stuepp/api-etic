<?php

declare(strict_types=1);

namespace App\Infrastructure\DatabaseException;

use Exception;
use Throwable;

class DatabaseException extends Exception implements DisplayMessageInterface
{
    private string $displayMessage;

    public function __construct(string $displayMessage, string $message, int $code = 500, Throwable $previous = null)
    {
        $this->displayMessage = $displayMessage;
        parent::__construct($message, $code, $previous);
    }

    public function getDisplayMessage(): string
    {
        return $this->displayMessage;
    }

}