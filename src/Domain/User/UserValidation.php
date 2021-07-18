<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\User;
use App\Domain\Validation;

class UserValidation extends Validation
{

    public function isValid(User $user): bool
    {
        $this->messages = [];
        if (!isset($user->name)) {
            $this->messages['name'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->password)) {
            $this->messages['password'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->address)) {
            $this->messages['address'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->city)) {
            $this->messages['city'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->email)) {
            $this->messages['email'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->birthday)) {
            $this->messages['birthday'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->company)) {
            $this->messages['company'] =  self::FIELD_NOT_SEND;
        }
        if (!isset($user->school)) {
            $this->messages['school'] =  self::FIELD_NOT_SEND;
        }
        if (!isset($user->taxId)) {
            $this->messages['taxId'] = self::FIELD_NOT_SEND;
        } else {
            if (!$this->validateTaxId($user->taxId)) {
                $this->messages['taxId'] = self::FIELD_INVALID;
            }
            $user->taxId = self::extractNumbers($user->taxId);
        }
        if (!isset($user->type)) {
            $user->type = User::TYPE_USER;
        } else {
            if (!in_array($user->type, [User::TYPE_ADMIN, User::TYPE_USER])) {
                $this->messages['type'] = self::FIELD_INVALID;
            } elseif (($user->type === User::TYPE_ADMIN) && ((!defined('USER_TYPE') || (USER_TYPE === User::TYPE_USER)))) {
                $this->messages['type'] = 'Valor invalido para usuario comum';
            }
        }
        if (!isset($user->disability)) {
            $user->disability = false;
        }


        return $this->validate();
    }
    public function canRead(int $id): bool
    {
        $this->messages = [];
        if ((USER_TYPE !== User::TYPE_ADMIN) && (USER_ID !== $id)) {
            $this->messages['message'] = 'Você não tem permissao para acessar esse registro';
        }
        return $this->validate();
    }

    public function canList(): bool
    {
        return $this->onlyAdmin();
    }

    public function canDelete(): bool
    {
        return $this->onlyAdmin();
    }
}
