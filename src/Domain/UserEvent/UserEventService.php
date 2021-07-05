<?php

declare(strict_types=1);

namespace App\Domain\UserEvent;

use App\Domain\User\User;
use App\Domain\Validation;
use App\Domain\Event\Event;
use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
use App\Domain\Factory\ParamsFactory;

class UserEventService extends ApplicationService implements IUserEventService
{

    private IUserEventRepository $repository;

    public function __construct(IUserEventRepository $repository)
    {
        $this->repository = $repository;
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
        $payload = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$event));
        if ($payload['total'] > 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Usuario já estava inscrito nesse evento']);
        }
        if (!$this->repository->save($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }

    public function update(int $user, int $event, array $data): ServicePayload
    {
        $userEvent = new UserEvent(['event' => $event, 'user' => $user]);
        if (!isset($userEvent->cheking)) {
            $userEvent->cheking = false;
        }
        $message = $this->checkUserEvent($userEvent);

        if ($message != null) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, $message);
        }
        $payload = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$event));
        if ($payload['total'] > 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Usuario já estava inscrito nesse evento']);
        }
        if (!$this->repository->save($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }

    public function delete(int $user, int $event): ServicePayload
    {
        $userEvent = new UserEvent(['event' => $event, 'user' => $user]);
        $message = $this->checkUserEvent($userEvent);

        if ($message != null) {
            $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, $message);
        }
        $payload = $this->repository->list(ParamsFactory::UserEvent()->setFilters('event', (string)$event)->setFilters('user', (string)$event));
        if ($payload['total'] === 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Usuario já estava inscrito nesse evento']);
        }
        if ($this->repository->remove($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Removido com Sucesso']);
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['userEvent' => 'Erro ao remover']);
        }
    }


    protected function checkUserEvent(UserEvent $userEvent): ?array
    {
        $message = null;
        if (!$this->repository->getUserById($userEvent->user->id)) {
            $message['user'] = 'Usuário não encontrado';
        }
        if (!$this->repository->getEventById($userEvent->event->id)) {
            $message['event'] = 'Evento não encontrado';
        }

        return $message;
    }

    public function list(?int $user, ?int $event): ServicePayload
    {
        $fields = 'team,cheking,waitlist';
        if ($event != null) {
            $fields .= ',user';
            return $this->ServicePayload(ServicePayload::STATUS_FOUND,  $this->repository->list(ParamsFactory::UserEvent()->setFields($fields)->setFilters('event', (string)$event)));
        } else {
            $fields .= ',event';
            return $this->ServicePayload(ServicePayload::STATUS_FOUND,  $this->repository->list(ParamsFactory::UserEvent()->setFields($fields)->setFilters('user', (string)$user)));
        }
    }
}
