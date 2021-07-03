<?php

declare(strict_types=1);


namespace App\Domain;

abstract class AbstractValidation
{

    protected const NOT_SEND = 'Campo obrigatorio não enviado';
    protected const INVALID = 'Valor do campo invalido';
    public const INVALID_ENTITY = 'Entidade invalida';
    public const DUPLICATE_ENTITY = 'Entidade duplicada';
    public const DUPLICATE_FIELD = 'Campo duplicado';
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
