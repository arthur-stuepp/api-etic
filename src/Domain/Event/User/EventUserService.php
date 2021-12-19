<?php

declare(strict_types=1);

namespace App\Domain\Event\User;

use App\Domain\Event\EventRepositoryInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Service\AbstractDomainService;
use App\Domain\Service\Payload;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Repository\EntityParams;
use Exception;

class EventUserService extends AbstractDomainService implements EventUserServiceInterface
{

    private EventRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        EventRepositoryInterface $repository,
        UserRepositoryInterface $userRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function addUser(int $userId, array $data): Payload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND);
        }
        $user = $this->userRepository->getById($userId);
        if (!$user) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND);
        }
        try {
            $event->addUser($user, $data['team'] ?? null);
        } catch (DomainException $e) {
            return $this->servicePayload($e->getCode(), ['message' => $e->getMessage()]);
        } catch (Exception $e) {
        }
        if (!$this->repository->save($event)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_VALID, $event->getUser($userId));
    }

    public function removeUser(int $eventId, int $userId): Payload
    {
        $event = $this->repository->getById($eventId);
        if ($event === null) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND, ['message' => self::NOT_FOUND]);
        }
        try {
            $event->removeUser($userId);
        } catch (DomainException $e) {
            return $this->servicePayload($e->getCode(), ['message' => $e->getMessage()]);
        }
        if (!$this->repository->save($event)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_VALID, $event->getUser($userId));
    }

    public function updateUser(int $userId, array $data): Payload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND, ['message' => self::NOT_FOUND]);
        }
        $user = $event->getUser($userId);
        if ($user === null) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND);
        }
        $user->setTeam((string)($data['team'] ?? $user->__get('team')));
        $user->setCheking((bool)($data['cheking'] ?? $user->__get('cheking')));
        if (!$this->repository->save($event)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_VALID, $event->getUser($userId));
    }

    public function readUser(int $eventId, int $userId): Payload
    {
        $event = $this->repository->getById($eventId);
        if (!$event) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND, ['message' => self::NOT_FOUND]);
        }
        $user = $event->getUser($userId);
        if ($user === null) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND);
        }
        return $this->servicePayload(Payload::STATUS_FOUND, $user);
    }

    public function list(int $eventId, array $params): Payload
    {
        $serviceParams = new EntityParams(EventUser::class, $params);
        return $this->servicePayload(Payload::STATUS_FOUND, $this->repository->listEventUser($eventId, $serviceParams));
    }
}
