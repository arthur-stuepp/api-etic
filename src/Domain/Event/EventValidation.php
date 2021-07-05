<?php


namespace App\Domain\Event;

use App\Domain\Validation;


class EventValidation extends Validation
{

    public function isValid(Event $event): bool
    {

        if (!isset($event->name)) {
            $this->messages['name'] = self::FIELD_NOT_SEND;
        }

        if (!isset($event->description)) {
            $this->messages['description'] = self::FIELD_NOT_SEND;
        }
        if (!isset($event->capacity)) {
            $this->messages['capacity'] = self::FIELD_NOT_SEND;
        } else {
            if ($event->capacity <= 0) {
                $this->messages['capacity'] = 'valor do campo precisa ser maior que 0';
            }
        }
        if (!isset($event->type)) {
            $event->type = Event::TYPE_EVENT;
        } else if (!in_array($event->type, [Event::TYPE_EVENT, Event::TYPE_GAME, Event::TYPE_GAME])) {
            $this->messages['type'] = self::FIELD_INVALID;
        }


        if (!isset($event->startTime)) {
            $this->messages['startTime'] = self::FIELD_INVALID;
        }
        if (!isset($event->endTime)) {
            $this->messages['endTime'] = self::FIELD_INVALID;
        }

        return $this->validate();
    }
}
