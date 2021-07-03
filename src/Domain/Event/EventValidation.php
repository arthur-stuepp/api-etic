<?php


namespace App\Domain\Event;

use App\Domain\AbstractValidation;


class EventValidation extends AbstractValidation
{

    public function isValid(Event $event): bool
    {

        if (!isset($event->name)) {
            $this->messages['name'] = self::NOT_SEND;
        }

        if (!isset($event->description)) {
            $this->messages['description'] = self::NOT_SEND;
        }
        if (!isset($event->capacity)) {
            $this->messages['capacity'] = self::NOT_SEND;
        } else {
            if ($event->capacity <= 0) {
                $this->messages['capacity'] = 'valor do campo precisa ser maior que 0';
            }
        }
        if (!in_array($event->type, [Event::TYPE_EVENT, Event::TYPE_GAME, Event::TYPE_GAME])) {
            $this->messages['type'] = self::INVALID;
        }

        if (!isset($event->startTime)) {
            $this->messages['startTime'] = self::INVALID;
        }
        if (!isset($event->endTime)) {
            $this->messages['endTime'] = self::INVALID;
        }

        return $this->validate();
    }
}
