<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\User;
use App\Domain\Validation;

class UserValidation extends Validation
{

    public function isValid(User $user): bool
    {
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
        if (!isset($user->disability)) {
            $user->disability = false;
        }


        return $this->validate();
    }
}
