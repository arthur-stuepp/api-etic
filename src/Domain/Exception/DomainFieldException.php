<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class DomainFieldException extends DomainException
{
    private string $field;

    public function __construct(string $message, string $field)
    {
        parent::__construct($message);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
