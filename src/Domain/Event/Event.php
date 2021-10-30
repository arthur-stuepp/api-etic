<?php /** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\General\Model\DateTimeModel;

class Event extends AbstractEntity
{
    public const TYPE_EVENT = 1;
    public const TYPE_GAME = 2;
    public const TYPE_HACKATHON = 3;

    protected int $id;

    protected string $name;

    protected int $type = self::TYPE_EVENT;

    protected string $description;

    protected int $capacity;

    protected DateTimeModel $startTime;

    protected DateTimeModel $endTime;

    /**
     * @var EventUser[]
     */
    private array $users = [];

    /**
     * @var int[]
     */
    private array $deleteIds=[];
    

    public function subscribeUser(EventUser $eventUser)
    {
        if ($this->getUser($eventUser->getUser()->getId()) === 0) {
            $this->users[] = $eventUser;
        }
    }

    public function getUser(int $userId): ?EventUser
    {
        return array_filter($this->users, function ($eventUser) use ($userId) {
                return $eventUser->getUser()->getId()=== $userId;
            })[0] ?? null;

    }

    public function unsubscribeUser(EventUser $eventUser)
    {
        $userId = $eventUser->getUser()->getId();
        foreach ($this->users as $key => $eventUser) {
            if ($eventUser->getUser()->getId() === $userId) {
                $this->deleteIds[] = $eventUser->id;
                unset($this->users[$key]);
                return;
            }
        }
    }
    


}
