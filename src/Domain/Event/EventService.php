<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\Exception\DomainException;
use App\Domain\RepositoryInterface;
use App\Domain\Service\AbstractCrudService;
use App\Domain\Service\Payload;
use App\Domain\User\UserRepositoryInterfaceInterface;
use App\Domain\Validator\InputValidator;
use Exception;

class EventService extends AbstractCrudService implements EventUserServiceInterface
{
    protected string $class;
    private EventRepositoryInterface $repository;
    private UserRepositoryInterfaceInterface $userRepository;

    public function __construct(
        InputValidator $validator,
        EventRepositoryInterface $repository,
        UserRepositoryInterfaceInterface $userRepository
    ) {
        parent::__construct($validator);
        $this->repository = $repository;
        $this->class = Event::class;
        $this->userRepository = $userRepository;
    }

    public function addUser(int $userId, array $data): Payload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
            return $this->servicePayload(Payload::STATUS_NOT_FOUND, ['message' => 'Usuario não encontrado']);
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

    public function removeUser(int $userId, array $data): Payload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
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

    protected function processEntity(AbstractEntity $entity): Payload
    {
        /** @noinspection PhpParamsInspection */
        if (!$this->repository->save($entity)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_VALID, $entity);
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
}
