<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\EntityInterface;
use App\Domain\General\Model\DateTimeModel;

class Event extends EntityInterface
{
    public const TYPE_EVENT = 1;
    public const TYPE_GAME = 2;
    public const TYPE_HACKATHON = 3;

    public int $id;

    public string $name;

    public int $type = self::TYPE_EVENT;

    public string $description;

    public int $capacity;

    public DateTimeModel $startTime;

    public DateTimeModel $endTime;

    /**
     * @var EventUser[]
     */
    private array $users = [];

    /**
     * @var int[]
     */
    private array $deleteIds=[];

    public function getUsers(): array
    {
        return $this->users ?? [];
    }
    

    public function addUser(EventUser $eventUser)
    {
        if ($this->getUser($eventUser->user->getId()) === 0) {
            $this->users[] = $eventUser;
        }
    }

    public function getUser(int $userId): ?EventUser
    {
        return array_filter($this->users, function ($eventUser) use ($userId) {
                return $eventUser->user->getId() === $userId;
            })[0] ?? null;

    }

    public function removeUser(EventUser $eventUser)
    {
        $userId = $eventUser->user;
        foreach ($this->users as $key => $eventUser) {
            if ($eventUser->user === $userId) {
                $this->deleteIds[] = $eventUser->id;
                unset($this->users[$key]);
                return;
            }
        }
    }
    


}
