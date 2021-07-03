<?php


declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\AbstractValidation;

class SchoolValidation extends AbstractValidation
{
    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(School $school): bool
    {
        if (!isset($school->name)) {
            $this->messages['name'] = self::NOT_SEND;
        }


        return $this->validate();
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }
}
