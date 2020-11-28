<?php


namespace App\Domain\School;


use App\Domain\ApplicationService;
use App\Domain\ServicePayload;

class SchoolService extends ApplicationService implements ISchoolService
{
   
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
        if ($this->repository->getByname($school->name)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['name' => 'Já existe uma escola com esse nome']);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->create($school)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $school = new School($data);

        if (!$this->validation->isValid($school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $school->id]);
    }

    public function read(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['school' => $this->repository->getById($id)]);
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['school' => 'Usuário não encontrado']);
    }

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['school' => 'Deletado com sucesso']);
            } else {
                return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['school' => 'Registro não pode ser deletado']);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['school' => 'Registro não encontrado']);
        }
    }
}