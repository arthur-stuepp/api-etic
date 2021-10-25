<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Services\ApplicationService;
use App\Domain\Services\EntityValidator;
use App\Domain\Services\ServicePayload;
use App\Domain\Services\Validator;
use App\Domain\Traits\TraitDeleteService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;


class SchoolService extends ApplicationService implements ISchoolService
{
    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    private EntityValidator $validation;
    private ISchoolRepository $repository;

    public function __construct(EntityValidator $validation, ISchoolRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
    }

    public function create(array $data): ServicePayload
    {
        $school = new School($data);
        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        $payload = $this->repository->list($this->params(School::class)->setFilters('name', $school->name));
//        if ($payload['total'] > 0) {
//            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validator::ENTITY_DUPLICATE, 'fields' => ['name' => Validator::FIELD_DUPLICATE]]);
//        }
        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validator::ENTITY_SAVE_ERROR, 'description' => $this->repository->getError()]);
        }


        return $this->ServicePayload(ServicePayload::STATUS_SAVED, ['id' => $school]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $school = new School($data);

        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        $payload = $this->repository->list($this->params(School::class)->setFilters('name', $school->name));
        if (($payload['total'] > 0) && ($payload['result'][0]->id !== $school->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['name' => Validation::FIELD_DUPLICATE]]);
        }
        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $school);
    }
}
