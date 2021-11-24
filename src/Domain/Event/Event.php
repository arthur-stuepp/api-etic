<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\DomainFieldException;
use App\Domain\Service\Payload;
use App\Domain\User\User;
use App\Domain\ValueObject\DateAndTime;
use ArrayObject;
use Exception;

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
    protected DateAndTime $startTime;
    protected DateAndTime $endTime;
    private ArrayObject $users;

    public function __construct(array $properties = [])
    {
        parent::__construct($properties);
        $this->users = new ArrayObject();
    }

    /**
     * @throws DomainException
     * @throws Exception
     */
    public function addUser(User $user, ?string $team = null): void
    {
        $userId = $user->getId();
        if ($this->users->offsetExists($userId)) {
            throw new DomainException('Usuario já inscrito nesse evento', Payload::STATUS_DUPLICATE_ENTITY);
        }
        $wailist = false;
        if ($this->users->count() >= $this->capacity) {
            $wailist = true;
        }
        $eventUser = new EventUser([
            'user' => $userId,
            'event' => $this->id,
            'team' => $team,
            'cheking' => false,
            'waitlist' => $wailist
        ]);
        $this->users->offsetSet($userId, $eventUser);
    }

    public function getUser(int $userId): ?EventUser
    {
        if (!$this->hasUser($userId)) {
            return null;
        }
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
    public function removeUser(int $userId): void
    {
        if (!$this->users->offsetExists($userId)) {
            throw new DomainException('Usuario Não encontrado nesse evento', Payload::STATUS_NOT_FOUND);
        }
        $this->users->offsetUnset($userId);
    }

    /**
     * @throws DomainFieldException
     */
    private function setType(int $type)
    {
        if (!in_array($type, [self::TYPE_EVENT, self::TYPE_GAME, self::TYPE_HACKATHON])) {
            throw new DomainFieldException('Tipo invalido', 'type');
        }
        $this->type = $type;
    }
}
