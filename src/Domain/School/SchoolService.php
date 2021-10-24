<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Services\ServicePayload;
use App\Domain\Services\ServiceListParams;;
use App\Domain\Validation;
use App\Domain\Services\ApplicationService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;
use App\Domain\Traits\TraitDeleteService;

class SchoolService extends ApplicationService implements ISchoolService
{
    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    private SchoolValidation $validation;
    private ISchoolRepository $repository;
    private ServiceListParams $params;

    public function __construct(SchoolValidation $validation, ISchoolRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->params = new ServiceListParams(School::class, []);
    }

    public function create(array $data): ServicePayload
    {
        $school = new School($data);
        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        $payload = $this->repository->list($this->params->setFilters('name', $school->name));
        if ($payload['total'] > 0) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['name' => Validation::FIELD_DUPLICATE]]);
        }
        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $school = new School($data);

        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        $payload = $this->repository->list($this->params->setFilters('name', $school->getName));
        if (($payload['total'] > 0) && ($payload['result'][0]->id !== $school->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['name' => Validation::FIELD_DUPLICATE]]);
        }
        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }
}
