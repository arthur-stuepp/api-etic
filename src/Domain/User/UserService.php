<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\City\ICityRepository;
use App\Domain\School\ISchoolRepository;
use App\Domain\Services\ApplicationService;
use App\Domain\Services\EntityValidator;
use App\Domain\Services\ServiceListParams;
use App\Domain\Services\ServicePayload;
use App\Domain\Services\Validator;
use App\Domain\Traits\TraitDeleteService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;


class UserService extends ApplicationService implements IUserService
{
    private EntityValidator $validation;
    private IUserRepository $repository;
    private ISchoolRepository $schoolRepository;
    private ICityRepository $cityRepository;
    private ServiceListParams $params;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    public function __construct(EntityValidator $validation, IUserRepository $repository, ISchoolRepository $schoolRepository, ICityRepository $cityRepository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->schoolRepository = $schoolRepository;
        $this->cityRepository = $cityRepository;
    }

    public function create(array $data): ServicePayload
    {
        $user = new User($data);

        if (!isset($user->disability)) {
            $user->disability = false;
        }
        if (isset($user->password)) {
            $user->password = password_hash($user->password, PASSWORD_BCRYPT);
        }


        return $this->processAndSave($user);
    }

    private function processAndSave(User $user): ServicePayload
    {
        if (!$this->validation->isValid($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['message' => Validator::ENTITY_INVALID, 'fields' => $this->validation->getMessages()]);
        }

        $field = $this->repository->getDuplicateField($user);
        if ($field !== null) {
            return $this->ServicePayload(ServicePayload::STATUS_DUPLICATE_ENTITY, ['message' => Validator::ENTITY_DUPLICATE, $field => Validator::FIELD_DUPLICATE]);
        }

        if (!$this->schoolRepository->getById($user->school->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validator::ENTITY_INVALID, 'school' => Validator::ENTITY_NOT_FOUND]);
        }
        if (!$this->cityRepository->getById($user->city->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validator::ENTITY_INVALID, 'city' => Validator::ENTITY_NOT_FOUND]);
        }
        if (!$this->repository->save($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validator::ENTITY_SAVE_ERROR, 'description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $user);
    }

    public function update(int $id, array $data): ServicePayload
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => Validator::ENTITY_NOT_FOUND]);
        }
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $user->setData($data);

        return $this->processAndSave($user);
    }
}
