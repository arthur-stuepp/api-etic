<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\ServicePayload;
use App\Domain\ServiceListParams;
use App\Domain\ApplicationService;
use App\Domain\Traits\TraitReadService;
use App\Domain\Traits\TraitDeleteService;


class EventService extends ApplicationService implements IEventService
{
    private EventValidation $validation;
    private IEventRepository $repository;

    use TraitDeleteService;
    use TraitReadService;


    public function __construct(EventValidation $validation, IEventRepository $repository)
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
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
        $event->setData($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }


        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $event->id]);
    }



    // public function list(ServiceListParams $params): ServicePayload
    // {
    //     return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->list($params)) ;
    // }
}
