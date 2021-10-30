<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\AbstractDomainService;
use App\Domain\General\Interfaces\CrudServiceInterface;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\ServicePayload;


class SchoolService extends AbstractDomainService implements CrudServiceInterface
{
    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    private InputValidator $validation;
    private SchoolRepositoryInterface $repository;
    private string $class;

    public function __construct(InputValidator $validation, SchoolRepositoryInterface $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->class = School::class;
    }

    public function create(array $data): ServicePayload
    {
        return $this->processAndSave($data);
    }

    private function processAndSave(array $data): ServicePayload
    {
        $school = new School($data);
        if (!$this->validation->isValid($data, $school)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }

        if (!$this->repository->save($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => self::SAVE_ERROR, 'description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $school);
    }

    public function update(int $id, array $data): ServicePayload
    {
        $school = $this->repository->getById($id);

        if (!$school) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        return $this->processAndSave($data);


    }
}
