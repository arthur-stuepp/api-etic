<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Services\ServicePayload;
use App\Domain\Services\ApplicationService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;
use App\Domain\Traits\TraitDeleteService;

class EventService extends ApplicationService implements IEventService
{
    private EventValidation $validation;
    private IEventRepository $repository;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(EventValidation $validation, IEventRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
    }

    public function create(array $data): ServicePayload
    {
        $event = new Event($data);

        if (!$this->validation->hasPermissionToSave()) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
        }

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => $this->validation->getMessages()]);
        }

        if ($this->validation->isDuplicateEntity($event, $this->repository->list($this->params(Event::class)->setFilters('name', $event->name)))) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['name' => Validation::FIELD_DUPLICATE]]);
        }

        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, ['id' => $this->repository->getError()]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        if (!$this->validation->hasPermissionToSave()) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
        }
        $event = $this->repository->getById($id);

        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
        $event->setData($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => $this->validation->getMessages()]);
        }
        if ($this->validation->isDuplicateEntity($event, $this->repository->list($this->params(Event::class)->setFilters('description', $event->description)))) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['description' => Validation::FIELD_DUPLICATE]);
        }


        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, ['id' => $this->repository->getError()]);
    }
}
