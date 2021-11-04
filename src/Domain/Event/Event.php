<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\DomainException\DomainException;
use App\Domain\General\Model\DateTimeModel;
use App\Domain\ServicePayload;
use App\Domain\User\User;
use ArrayObject;

class Event extends AbstractEntity
{
    public const TYPE_EVENT = 1;
    public const TYPE_GAME = 2;
    public const TYPE_HACKATHON = 3;
    protected int $id;
    protected string $name;
    protected int $type;
    protected string $description;
    protected int $capacity;
    protected DateTimeModel $startTime;
    protected DateTimeModel $endTime;
    private ArrayObject $users;

    public function __construct(array $properties = [])
    {
        parent::__construct($properties);
        $this->users = new ArrayObject();
    }

    /**
     * @throws DomainException
     */
    public function addUser(User $user): void
    {
        $userId = $user->getId();
        if ($this->users->offsetExists($userId)) {
            throw new DomainException('Usuario já inscrito nesse evento', ServicePayload::STATUS_DUPLICATE_ENTITY);
        }
        $eventUser = new EventUser(['user' => $userId, 'event' => $this->id]);
        if ($this->users->count() >= $this->capacity) {
            $eventUser->setWaitlist(true);
        }
        $this->users[$userId] = $eventUser;
    }

    public function getUser(int $userId): ?EventUser
    {
        return $this->users->offsetGet($userId);

    }

    public function hasUser(int $userId): bool
    {
        return $this->users->offsetExists($userId);

    }

    /**
     * @return EventUser[]
     */
    public function getUsers(): array
    {
        return $this->users->getArrayCopy();

    }

    /**
     * @throws DomainException
     */
    public function removeUser(User $user): void
    {
        $userId = $user->getId();
        if (!$this->users->offsetExists($userId)) {
            throw new DomainException('Usuario Não encontrado nesse evento', ServicePayload::STATUS_NOT_FOUND);
        }

        $this->users->offsetUnset($userId);
    }


}
