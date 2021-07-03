<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
use App\Domain\ServiceListParams;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;
use App\Domain\Traits\TraitDeleteService;

class EventService extends ApplicationService implements IEventService
{
    private EventValidation $validation;
    private IEventRepository $repository;
    private ServiceListParams $params;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(EventValidation $validation, IEventRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->params = new ServiceListParams(Event::class);
    }

    public function create(array $data): ServicePayload
    {
        $event = new Event($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => $this->validation->getMessages()]);
        }
        if (($this->repository->list($this->params->setFilters('description', $event->description))['total'] > 0)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => ['description' => 'Campo duplicado']]);
        }
        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $event = $this->repository->getById($id);

        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
        $event->setData($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => $this->validation->getMessages()]);
        }
        $payload = $this->repository->list($this->params->setFilters('description', $event->description));
        if (($payload['total'] > 0) && $payload['result'][0]->id !== $event->id) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => ['description' => 'Campo duplicado']]);
        }


        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }
}
