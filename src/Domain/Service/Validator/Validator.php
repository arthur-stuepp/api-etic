<?php

declare(strict_types=1);


namespace App\Domain\Service\Validator;

abstract class Validator
{

    public const FIELD_REQUIRED = 'Campo obrigatorio';
    public const FIELD_INVALID = 'Valor invalido';


    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }
}
