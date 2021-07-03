<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
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

    public function __construct(SchoolValidation $validation, ISchoolRepository $repository)
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
        if ($this->repository->getByName($school->name)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['name' => 'Já existe uma escola com esse nome']);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->save($school)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $school = new School($data);

        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $school->id]);
    }


}