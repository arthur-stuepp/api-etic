<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Services\ApplicationService;
use App\Domain\Services\EntityValidator;
use App\Domain\Services\ServicePayload;
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
        return $this->validateAndSave($school);
    }

    private function validateAndSave(School $school): ServicePayload
    {
        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, $this->validation->getMessages());
        }
        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => self::ENTITY_SAVE_ERROR, 'description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $school);
    }

    public function update(int $id, array $data): ServicePayload
    {
        $school = $this->repository->getById($id);

        if (!$school) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => self::ENTITY_NOT_FOUND]);
        }
        return $this->validateAndSave($school);


    }
}
