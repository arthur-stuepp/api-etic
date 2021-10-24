<?php


namespace App\Domain\UserEvent;

use App\Domain\Services\ServicePayload;


interface IUserEventService
{
    public function create(int $user, int $event, array $data): ServicePayload;

    public function update(int $user, int $event, array $data): ServicePayload;

    public function delete(int $user, int $event): ServicePayload;

    public function getUsersEvent(int $event): ServicePayload;
    
    public function getEventsUser(int $user): ServicePayload;

}
