<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Services\ApplicationService;
use App\Domain\Services\EntityValidator;
use App\Domain\Services\ServicePayload;
use App\Domain\Traits\TraitDeleteService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;

class EventService extends ApplicationService implements IEventService
{
    private EntityValidator $validator;
    private IEventRepository $repository;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(EntityValidator $validator, IEventRepository $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }

    public function create(array $data): ServicePayload
    {
        $event = new Event($data);

        return $this->validateAndSave($event);
    }

    private function validateAndSave(Event $event): ServicePayload
    {
        if (!$this->validator->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => 'Evento invalido', 'fields' => $this->validator->getMessages()]);
        }
        
        if (!$this->repository->save($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $event);
    }

    public function update(int $id, array $data): ServicePayload
    {

        $event = $this->repository->getById($id);

        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
        $event->setData($data);
        return $this->validateAndSave($event);


    }
}
