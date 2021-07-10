<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\User;
use App\Domain\Validation;

class AuthValidation extends Validation
{
    public function isValid(User $user)
    {
        if (!isset($user->email)) {
            $this->messages['email'] = self::FIELD_NOT_SEND;
        }
        if (!isset($user->password)) {
            $this->messages['password'] =  self::FIELD_NOT_SEND;
        }
        return $this->validate();
    }
}
