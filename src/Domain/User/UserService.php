<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Validation;
use App\Domain\ServicePayload;
use App\Domain\ServiceListParams;
use App\Domain\ApplicationService;
use App\Domain\City\ICityRepository;
use App\Domain\Factory\ParamsFactory;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;
use App\Domain\School\ISchoolRepository;
use App\Domain\Traits\TraitDeleteService;


class UserService extends ApplicationService implements IUserService
{
    private UserValidation $validation;
    private IUserRepository $repository;
    private ISchoolRepository $schoolRepository;
    private ICityRepository $cityRepository;
    private ServiceListParams $params;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    public function __construct(UserValidation $validation, IUserRepository $repository, ISchoolRepository $schoolRepository, ICityRepository $cityRepository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->schoolRepository = $schoolRepository;
        $this->cityRepository = $cityRepository;
        $this->params = new ServiceListParams(User::class);
    }

    public function create(array $data): ServicePayload
    {
        $user = new User($data);
        return $this->processAndSave($user);
    }


    public function update(int $id, array $data): ServicePayload
    {
        if (!$this->validation->canRead($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, $this->validation->getMessages());
        }
        $user = $this->repository->getById($id);

        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => Validation::ENTITY_NOT_FOUND]);
        }
        $user->setData($data);

        return $this->processAndSave($user);
    }


    private function processAndSave(User $user): ServicePayload
    {
        if (!$this->validation->isValid($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID,['message'=>Validation::ENTITY_INVALID,'fields'=> $this->validation->getMessages()]);
        }
        if (!$this->schoolRepository->getById($user->school->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['school' => Validation::ENTITY_NOT_FOUND]);
        }
        if ($this->validation->isDuplicateEntity($user, $this->repository->list(ParamsFactory::User()->setFilters('email', $user->email)))) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['email' => Validation::FIELD_DUPLICATE]]);
        }
        if ($this->validation->isDuplicateEntity($user, $this->repository->list(ParamsFactory::User()->setFilters('taxId', Validation::extractNumbers($user->taxId))))) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID,  ['message' => Validation::ENTITY_DUPLICATE, 'fields' => ['taxId' => Validation::FIELD_DUPLICATE]]);
        }
        if (!$this->cityRepository->getById($user->city->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['city' => Validation::ENTITY_NOT_FOUND]);
        }
        $user->password=password_hash($user->password,PASSWORD_BCRYPT); 
        if (!$this->repository->save($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['message' => Validation::ENTITY_SAVE_ERROR, 'description' => $this->repository->getLastError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->getLastSaveId()]);
    }
}
