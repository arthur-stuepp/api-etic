<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractDomainService;
use App\Domain\DomainException\DomainException;
use App\Domain\CrudServiceInterface;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\ServicePayload;
use App\Domain\User\UserRepositoryInterface;

class EventService extends AbstractDomainService implements CrudServiceInterface
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
        $event = new Event($data);
        $event->setId($id);


        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $event);

    }

    public function enrollUser(int $userId, array $data): ServicePayload
    {
        return $this->processEnroll($userId, $data, 'roll');

    }   
    public function unEnrollUser(int $userId, array $data): ServicePayload
    {
        return $this->processEnroll($userId, $data, 'enRoll');

    }

    private function processEnroll(int $userId, array $data, string $method): ServicePayload
    {
        if (isset($data['event'])) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['event' => 'Evento não enviado']);
        }

        $event = $this->repository->getById($data['event']);
        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['event' => self::NOT_FOUND]);
        }

        $user = $this->userRepository->getById($userId);
        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => self::NOT_FOUND]);
        }

        try {
            $event->$method($user);
        } catch (DomainException $e) {
            return $this->ServicePayload($e->getCode(), $e->getMessage());
        }

        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, $this->repository->getError());
        }

        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['id' => $user]);

    }

}
