<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Service\AbstractCrudService;
use App\Domain\AbstractEntity;
use App\Domain\Exception\DomainException;
use App\Domain\RepositoryInterface;
use App\Domain\Service\ServicePayload;
use App\Domain\User\UserRepositoryInterfaceInterface;
use App\Domain\Validator\InputValidator;

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

    public function addUser(int $userId, array $data): ServicePayload
    {
        return $this->processUser($userId, $data, 'addUser');
    }

    private function processUser(int $userId, array $data, string $method): ServicePayload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => self::NOT_FOUND]);
        }
        $user = $this->userRepository->getById($userId);
        if (!$user) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => self::NOT_FOUND]);
        }
        try {
            if (in_array($method, ['addUser', 'removeUser'])) {
                $event->$method($user);
            } else {
                $user = $event->getUser($userId);
                if (isset($data['team'])) {
                    $user->setTeam((string)$data['team']);
                }
                if (isset($data['cheking'])) {
                    $user->setCheking((bool)$data['cheking']);
                }
            }
        } catch (DomainException $e) {
            return $this->servicePayload($e->getCode(), ['message' => $e->getMessage()]);
        }
        if (!$this->repository->save($event)) {
            return $this->servicePayload(
                ServicePayload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        try {
            return $this->servicePayload(ServicePayload::STATUS_VALID, ['id' => $event->getUser($userId)]);
        } catch (DomainException $e) {
            return $this->servicePayload($e->getCode(), ['message' => $e->getMessage()]);
        }
    }

    public function removeUser(int $userId, array $data): ServicePayload
    {
        return $this->processUser($userId, $data, 'removeUser');
    }

    public function updateUser(int $userId, array $data): ServicePayload
    {
        return $this->processUser($userId, $data, 'updateUser');
    }

    /** @noinspection PhpParamsInspection */
    protected function processEntity(AbstractEntity $entity): ServicePayload
    {
        if (!$this->repository->save($entity)) {
            return $this->servicePayload(
                ServicePayload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(ServicePayload::STATUS_VALID, $entity);
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
}
