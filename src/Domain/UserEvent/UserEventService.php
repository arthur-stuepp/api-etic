<?php


namespace App\Domain\UserEvent;


use App\Domain\ApplicationService;
use App\Domain\Event\IEventRepository;
use App\Domain\ServicePayload;
use App\Domain\User\IUserRepository;

class UserEventService extends ApplicationService implements IUserEventService
{

    private IUserEventRepository $repository;

    private IEventRepository $eventRepository;

    private IUserRepository $userRepository;

    public function __construct(IUserEventRepository $repository, IEventRepository $eventRepository, IUserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    public function add(int $user, int $event): ServicePayload
    {
        $userEvent = new UserEvent(['event' => $event, 'user' => $user]);
        $message = $this->checkUserEvent($userEvent);

        if ($message != null) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, $message);
        }
        if ($this->repository->getUserEvent($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['userEvent' => 'Usuario já estava inscrito nesse evento']);
        }
        $this->repository->add($userEvent);
        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['userEvent' => 'adicionado com Sucesso']);

    }

    public function remove(int $user, int $event): ServicePayload
    {
        $userEvent = new UserEvent(['event' => $event, 'user' => $user]);
        $message = $this->checkUserEvent($userEvent);

        if ($message != null) {
            $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, $message);
        }
        if (!$this->repository->getUserEvent($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['userEvent' => 'Usuario não estava inscrito nesse evento']);
        }
        if ($this->repository->remove($userEvent)) {
            return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['userEvent' => 'Removido com Sucesso']);
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['userEvent' => 'Erro ao remover']);
        }
    }


    protected function checkUserEvent(UserEvent $userEvent): ?array
    {
        $message = null;
        if (!$this->userRepository->getById($userEvent->user)) {
            $message['user'] = 'Usuário não encontrado';
        }
        if (!$this->eventRepository->getById($userEvent->event)) {
            $message['event'] = 'Evento não encontrado';
        }

        return $message;
    }

    public function list(?int $user, ?int $event): ServicePayload
    {
        if ($event != null) {

            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['users' => $this->repository->getUsersByEvent($event)]);
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['events' => $this->repository->getEventsByUser($user)]);
        }

    }


}