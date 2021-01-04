<?php

namespace App\Domain\UserHackthon;

class HackthonValidation
{
    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(UserHackthon $userHackthon): bool
    {
        if (!isset($userHackthon->name)) {
            $this->messages['name'] = 'Nome não pode ser vazio.';
        }
        if (!isset($userHackthon->capacity)) {
            $this->messages['capacity'] = 'Capacidade não pode ser vazio.';
        }
        if (!isset($userHackthon->waitlist)) {
            $this->messages['waitlist'] = 'Lista de espera não pode ser vazio.';
        }

        return $this->validate();
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }

}
