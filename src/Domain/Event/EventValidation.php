<?php


namespace App\Domain\Event;

use App\Domain\AbstractValidation;


class EventValidation extends AbstractValidation
{

    public function isValid(Event $event): bool
    {
        if (!isset($event->description)) {
            $this->messages['description'] = self::NOT_SEND;
        }
        if (!isset($event->capacity)) {
            $this->messages['capacity'] = self::NOT_SEND;
        } else {
            if ($event->capacity <= 0) {
                $this->messages['capacity'] = 'Propriedade precisa ser mais que 0';
            }
        }
    
        if (!isset($event->startTime)) {
            $this->messages['startTime'] = 'Horario de inicio invalido';
        }
        if (!isset($event->endTime)) {
            $this->messages['endTime'] = 'Horario de fim invalido';
        }

        return $this->validate();
    }
}
