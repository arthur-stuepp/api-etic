<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\AbstractDomainService;
use App\Domain\General\Interfaces\ICrudService;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\ServicePayload;


class SchoolService extends AbstractDomainService implements ICrudService
{
    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    private InputValidator $validation;
    private ISchoolRepository $repository;
    private string $class;

    public function __construct(InputValidator $validation, ISchoolRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->class = School::class;
    }

    public function create(array $data): ServicePayload
    {
        return $this->processAndSave($data, new School());
    }

    private function processAndSave(array $data, School $school): ServicePayload
    {
        if (!$this->validation->isValid($data, $school)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }
        $school->setData($data);

        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => self::ENTITY_SAVE_ERROR, 'description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $school);
    }

    public function update(int $id, array $data): ServicePayload
    {
        $school = $this->repository->getById($id);

        if (!$school) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->processAndSave($data, $school);


    }
}
