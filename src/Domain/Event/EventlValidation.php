<?php


namespace App\Domain\Event;


class EventlValidation
{
    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(Event $event): bool
    {
        if (!isset($event->description)) {
            $this->messages['description'] = 'Descrição não pode ser vazio';
        }
        if (!isset($event->capacity)) {
            $this->messages['capacity'] = 'Capacidade não pode ser vazio';
        }
        if (!isset($event->startTime)) {
            $this->messages['startTime'] = 'Horario de inicio invalido';
        }
        if (!isset($event->endTime)) {
            $this->messages['endTime'] = 'Horario de fim invalido';
        }

        return $this->validate();
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }


}