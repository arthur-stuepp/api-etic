<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractDomainService;
use App\Domain\General\Interfaces\CrudServiceInterface;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\ServicePayload;

class EventService extends AbstractDomainService implements CrudServiceInterface
{
    private InputValidator $validator;
    private EventRepositoryInterface $repository;
    private string $class;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(InputValidator $validator, EventRepositoryInterface $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->class = Event::class;
    }

    public function create(array $data): ServicePayload
    {
        return $this->validateAndSave($data);
    }

    private function validateAndSave(array $data): ServicePayload
    {
        $event = new Event($data);
        if (!$this->validator->isValid($data, $event)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['fields' => $this->validator->getMessages()]);
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
