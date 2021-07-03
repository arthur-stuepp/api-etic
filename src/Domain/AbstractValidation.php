<?php

declare(strict_types=1);


namespace App\Domain;

abstract class AbstractValidation
{

    protected const NOT_SEND='Propriedade Não enviada';
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
