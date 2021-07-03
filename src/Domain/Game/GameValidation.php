<?php

declare(strict_types=1);

namespace App\Domain\Game;

use App\Domain\AbstractValidation;


class EventValidation extends AbstractValidation
{

    public function isValid(Game $game): bool
    {
        if (!isset($game->name)) {
            $this->messages['name'] = self::NOT_SEND;
        }

        if (!isset($game->capacity)) {
            $this->messages['capacity'] = self::NOT_SEND;
        }
        if (!isset($game->name)) {
            $this->messages['name'] = self::NOT_SEND;
        }

        return $this->validate();
    }
}
