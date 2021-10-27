<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\ApplicationService;
use App\Domain\General\Interfaces\ICrudService;
use App\Domain\General\Validator\EntityValidator;
use App\Domain\ServicePayload;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;


class SchoolService extends ApplicationService implements ICrudService
{
    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    private EntityValidator $validation;
    private ISchoolRepository $repository;
    private string $class;

    public function __construct(EntityValidator $validation, ISchoolRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->class=School::class;
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
