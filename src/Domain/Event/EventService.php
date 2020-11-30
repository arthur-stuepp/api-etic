<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Service\ServiceListParams;
use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
use function DI\value;


class EventService extends ApplicationService implements IEventService
{
    private EventlValidation $validation;

    private IEventRepository $repository;


    public function __construct(EventlValidation $validation, IEventRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
    }

    public function create(array $data): ServicePayload
    {
        $event = new Event($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->create($event)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $event = $this->repository->getById($id);

        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['event' => 'Evento não encontrado']);
        }
        $event->setData($data);


        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $event->id]);
    }

    public function read(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['event' => $this->repository->getById($id)]);
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['event' => 'Evento não encontrado']);
    }

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['event' => 'Deletado com sucesso']);
            } else {
                return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['event' => 'Registro não pode ser deletado']);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['event' => 'Evento não encontrado']);
        }
    }

    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['events' => 'events']);
    }


}
