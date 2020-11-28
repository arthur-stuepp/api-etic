<?php

namespace App\Domain\User;

use App\Domain\User\User;

class UserValidation
{
    protected $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(User $user): bool
    {
        if (!isset($user->name)) {
            $this->messages['name'] = 'Nome não pode ser vazio.';
        }
        if (!isset($user->password)) {
            $this->messages['password'] = 'Senha não pode ser vazio.';
        }
        if (!isset($user->address)) {
            $this->messages['address'] = 'Endereço não pode ser vazio.';
        }
        if (!isset($user->city)) {
            $this->messages['city'] = 'Cidade não pode ser vazio.';
        }
        if (!isset($user->email)) {
            $this->messages['email'] = 'Email não pode ser vazio.';
        }
        if (!isset($user->birthday)) {
            $this->messages['birthday'] = 'Data de nascimento não pode ser vazio.';
        }
        if (!isset($user->company)) {
            $this->messages['company'] = 'Empresa não pode ser vazio.';
        }
        if (!isset($user->school)) {
            $this->messages['school'] = 'Escola não pode ser vazio.';
        }
        if (!isset($user->taxId)) {
            $this->messages['taxId'] = 'CPF não pode ser vazio.';
        }
        if (!isset($user->disability)) {
            $this->messages['disability'] = 'Deficiencia não pode ser vazio.';
        }


        return $this->validate();
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }

    public function forAuth(User $user)
    {
        if (!$user->email) {
            $this->messages['email'] = 'Email não pode ser vazio.';
        } else if (!$user->password) {
            $this->messages['password'] = 'Senha não pode ser vazio.';
        }
        return $this->validate();
    }
}
