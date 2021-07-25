<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use PDO;
use App\Infrastructure\DB\DB;
use App\Domain\ServiceListParams;
use App\Domain\UserEvent\UserEvent;
use App\Domain\User\IUserRepository;
use App\Domain\Factory\ParamsFactory;
use App\Domain\Event\IEventRepository;
use App\Domain\UserEvent\IUserEventRepository;

class UserEventRepository extends MysqlRepository implements IUserEventRepository
{
    private IUserRepository $userRepository;
    private IEventRepository $eventRepository;
    public function __construct(DB $db, IUserRepository $userRepository, IEventRepository $eventRepository)
    {
        parent::__construct($db);
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getClass(): string
    {
        return UserEvent::class;
    }

    public function save(UserEvent $userEvent): bool
    {
        return $this->saveEntity($userEvent);
    }


    public function getUserById(int $id)
    {
        return $this->userRepository->getById($id);
    }

    public function getEventById(int $id)
    {
        return $this->eventRepository->getById($id);
    }

    public function list(ServiceListParams $params): array
    {
        $payload = parent::list($params);
        foreach ($payload['result'] as $entity) {
            if (in_array('user', $params->getFields())) {
                $entity->user = $this->userRepository->list(ParamsFactory::UserId($entity->user->id))['result'][0];
            }
            if (in_array('event', $params->getFields())) {
                $entity->event = $this->eventRepository->list(ParamsFactory::EventId($entity->event->id))['result'][0];
            }
        }
        return $payload;
    }
}
