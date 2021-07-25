<?php

declare(strict_types=1);

namespace App\Domain\UserEvent;

use App\Domain\User\User;
use App\Domain\Validation;
use App\Domain\Event\Event;
use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
use App\Domain\Factory\ParamsFactory;
use App\Domain\UserEvent\UserEventValidation;

class UserEventService extends ApplicationService implements IUserEventService
{

    private IUserEventRepository $repository;
    private UserEventValidation $validation;

    public function __construct(IUserEventRepository $repository,UserEventValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function create(int $user, int $event, array $data): ServicePayload
    {
        $userEvent = new UserEvent($data);
        $userEvent->user = new User(['id' => $user]);
        $userEvent->event = new Event(['id' => $event]);
        if (!isset($userEvent->cheking)) {
            $userEvent->cheking = false;
        }
        $userEvent->waitlist = false;
        $message = $this->checkUserEvent($userEvent);

        if ($message != null) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, $message);
        }
        $payload = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$user)->setLimit(1));
        if ($payload['total'] > 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Usuario já esta inscrito nesse evento']);
        }
        $payload = $this->repository->list(
            $this->params(UserEvent::class)
                ->setFields('user,team,cheking,waitlist')
                ->setFilters('event', (string)$event)
        );
        if ($payload['total'] >= $userEvent->event->capacity) {
            $userEvent->waitlist = true;
        }

        if (!$this->repository->save($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' =>$this->repository->getLastSaveId()]);
    }

    public function update(int $user, int $event, array $data): ServicePayload
    {
        $userEvent = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$user)->setLimit(1))['result'][0] ?? false;
        if (!$userEvent) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => Validation::ENTITY_NOT_FOUND]);
        }
        $userEvent->setData($data);

        if (!$this->repository->save($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }

    protected function checkUserEvent(UserEvent $userEvent): ?array
    {
        $message = null;
        if (!$this->repository->getUserById($userEvent->user->id)) {
            $message['user'] = 'Usuário não encontrado';
        }
        $event = $this->repository->getEventById($userEvent->event->id);
        if (!$event) {
            $message['event'] = 'Evento não encontrado';
        } else {
            $userEvent->event = $event;
        }

        return $message;
    }

    public function getUsersEvent(int $event): ServicePayload
    {
        if (!$this->validation->hasPermissionToReadUsersEvent()) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
        }

        if (!$this->repository->getEventById($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => Validation::ENTITY_NOT_FOUND]);
        }
        return $this->ServicePayload(
            ServicePayload::STATUS_FOUND,
            $this->repository->list(
                $this->params(UserEvent::class)
                    ->setFields('user,team,cheking,waitlist')
                    ->setFilters('event', (string)$event)
            )
        );
    }

    public function getEventsUser(int $user): ServicePayload
    {
        if(!$this->validation->hasPermissionToReadEventsUser($user)){
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN,$this->validation->getMessages());
        }

        if (!$this->repository->getUserById($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => Validation::ENTITY_NOT_FOUND]);
        }
        return $this->ServicePayload(
            ServicePayload::STATUS_FOUND,
            $this->repository->list(
                $this->params(UserEvent::class)
                    ->setFields('event,team,cheking,waitlist')
                    ->setFilters('user', (string)$user)
            )
        );
    }

    public function read(int $user, int $event): ServicePayload
    {
        $payload = $this->repository->list(
            $this->params(UserEvent::class)
                ->setFilters('user', (string)$user)
                ->setFilters('event', (string)$event)
        );
        if ($payload['total'] === 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => Validation::ENTITY_NOT_FOUND]);
        }
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, $payload['result'][0]);
    }


    public function delete(int $user, int $event): ServicePayload
    {

        if (!$this->validation->hasPermissionToDelete($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
        }

        $userEvent = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$user)->setLimit(1))['result'][0] ?? false;
        if (!$userEvent) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => Validation::ENTITY_NOT_FOUND]);
        }

        if ($this->repository->delete($userEvent->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Removido com Sucesso']);
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['userEvent' => 'Erro ao remover']);
        }
    }
}
