<?php


namespace App\Domain\School;


class SchoolValidation
{
    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(School $school): bool
    {
        if (!isset($school->name)) {
            $this->messages['name'] = 'Nome não pode ser vazio.';
        }


        return $this->validate();
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }


}