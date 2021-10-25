<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Services\Validator;
use App\Domain\User\User;

class AuthValidation extends Validator
{
    public function isValid(User $user): bool
    {
        if (!isset($user->email)) {
            $this->messages['email'] = self::FIELD_REQUIRED;
        }
        if (!isset($user->password)) {
            $this->messages['password'] =  self::FIELD_REQUIRED;
        }
        return $this->validate();
    }
}
