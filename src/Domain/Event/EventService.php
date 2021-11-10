<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractDomainService;
use App\Domain\CrudServiceInterface;
use App\Domain\DomainException\DomainException;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\ServicePayload;
use App\Domain\User\UserRepositoryInterface;

class EventService extends AbstractDomainService implements CrudServiceInterface, EventUserServiceInterface
{
    private InputValidator $validator;
    private EventRepositoryInterface $repository;
    private string $class;
    private UserRepositoryInterface $userRepository;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(InputValidator $validator, EventRepositoryInterface $repository, UserRepositoryInterface $userRepository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->class = Event::class;
        $this->userRepository = $userRepository;
    }

    public function create(array $data): ServicePayload
    {
        $data['type'] = $data['type'] ?? Event::TYPE_EVENT;
        if (!$this->validator->isValid($data, new Event())) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validator->getMessages()]);
        }
        $event = new Event($data);

        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $event);
    }


    public function update(int $id, array $data): ServicePayload
    {

        $event = $this->repository->getById($id);
        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }

        if (!$this->validator->isValid($data, new Event())) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validator->getMessages()]);
        }
        $data['id'] = $id;
        $event = new Event($data);


        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $event);

    }

    public function addUser(int $userId, array $data): ServicePayload
    {
        return $this->processUser($userId, $data, 'addUser');

    }

    private function processUser(int $userId, array $data, string $method): ServicePayload
    {
        $event = $this->repository->getById((int)$data['event']);
        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => self::NOT_FOUND]);
        }
        $user = $this->userRepository->getById($userId);
        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => self::NOT_FOUND]);
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
            return $this->ServicePayload($e->getCode(), ['message' => $e->getMessage()]);
        }
        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }
        try {
            return $this->ServicePayload(ServicePayload::STATUS_VALID, ['id' => $event->getUser($userId)]);
        } catch (DomainException $e) {
            return $this->ServicePayload($e->getCode(), ['message' => $e->getMessage()]);
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


}
