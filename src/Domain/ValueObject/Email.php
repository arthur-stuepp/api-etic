<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Exception;

class Email implements ValueObjectInterface
{
    private string $email;

    /**
     * @throws Exception
     */
    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email invalido');
        }
        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function jsonSerialize(): string
    {
        return substr($this->email, 0, 3) . '****' . substr($this->email, strpos($this->email, "@"));
    }
}
