<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Address\IAddressRepository;
use App\Domain\ApplicationService;
use App\Domain\General\Interfaces\IAuthService;
use App\Domain\General\Interfaces\ICrudService;
use App\Domain\General\ServiceListParams;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\EntityValidator;
use App\Domain\School\ISchoolRepository;
use App\Domain\ServicePayload;
use Firebase\JWT\JWT;


class UserService extends ApplicationService implements ICrudService, IAuthService
{
    private EntityValidator $validation;
    private IUserRepository $repository;
    private ISchoolRepository $schoolRepository;
    private IAddressRepository $addressRepository;
    private ServiceListParams $params;
    private string $class;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    public function __construct
    (
        EntityValidator    $validation,
        IUserRepository    $repository,
        ISchoolRepository  $schoolRepository,
        IAddressRepository $addressRepository
    )
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->schoolRepository = $schoolRepository;
        $this->addressRepository = $addressRepository;
        $this->class = User::class;
    }

    public function create(array $data): ServicePayload
    {

        $user = new User($data);

        if (isset($user->password)) {
            $user->password = password_hash($user->password, PASSWORD_BCRYPT);
        }


        return $this->processAndSave($user);
    }

    private function processAndSave(User $user): ServicePayload
    {
        if (!$this->validation->isValid($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }

        $field = $this->repository->getDuplicateField($user);
        if ($field !== null) {
            return $this->ServicePayload(ServicePayload::STATUS_DUPLICATE_ENTITY, ['field' => $field]);
        }

        if (!$this->schoolRepository->getById($user->school->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['school' => self::ENTITY_NOT_FOUND]);
        }

        if (!$this->addressRepository->getCityById($user->city->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['city' => self::ENTITY_NOT_FOUND]);
        }

        if (!$this->repository->save($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }

        return $this->ServicePayload(ServicePayload::STATUS_SAVED, $user);
    }

    public function update(int $id, array $data): ServicePayload
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $user->setData($data);


        return $this->processAndSave($user);
    }

    public function auth(array $data): ServicePayload
    {
        $userAuth = new User($data);
        $user = $this->repository->list($this->params(User::class)->setFilters('email', $userAuth->email)->setLimit(1))['result'][0] ?? false;
        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Usuario não existente']);
        }
        if (!password_verify($userAuth->password, $user->password)) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Senha incorreta.']);
        }
        $token = $this->tokenGenerate($user);

        return $this->ServicePayload(ServicePayload::STATUS_VALID, ['token' => $token, 'user' => $user]);
    }

    private function tokenGenerate(User $user): string
    {

        $token = [
            'iss' => 'https://' . $_SERVER['HTTP_HOST'],
            'iat' => time(),
            'exp' => strtotime('+1 day', time()),
            'user' => $user->id,
            'type' => $user->type,

        ];

        return JWT::encode($token, KEY);
    }
}
