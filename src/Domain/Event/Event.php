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
    protected int $type = self::TYPE_EVENT; 
    protected string $description;
    protected int $capacity;
    protected DateTimeModel $startTime;
    protected DateTimeModel $endTime;
    private ArrayObject $class;

    public function __construct(array $properties = [])
    {
        parent::__construct($properties);
        $this->class=new ArrayObject();
    }

    /**
     * @throws DomainException
     */
    public function enroll(User $user): void
    {
        $userId = $user->getId();
        if ($this->class->offsetExists($userId)) {
            throw new DomainException('Usuario já inscrito nesse evento', ServicePayload::STATUS_DUPLICATE_ENTITY);
        }
        $eventUser = new EventUser(['user' => $userId]);
        if ($this->class->count() > $this->capacity) {
            $eventUser->setWaitlist(true);
        }
        $this->class[$userId] = $eventUser;
    }

    public function getEnroll(int $userId): ?EventUser
    {
        return $this->class->offsetGet($userId);

    }

    public function getClass(): array
    {
        return $this->class->getArrayCopy();

    }

    /**
     * @throws DomainException
     */
    public function unEnroll(User $user): void
    {
        $userId = $user->getId();
        if (!$this->class->offsetExists($userId)) {
            throw new DomainException('Usuario Não encontrado nesse evento', ServicePayload::STATUS_NOT_FOUND);
        }
        
        $this->class->offsetUnset($userId);
    }


}
