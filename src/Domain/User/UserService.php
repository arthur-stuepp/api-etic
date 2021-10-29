<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Address\IAddressRepository;
use App\Domain\AbstractDomainService;
use App\Domain\General\Interfaces\IAuthService;
use App\Domain\General\Interfaces\ICrudService;
use App\Domain\General\ServiceListParams;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\School\ISchoolRepository;
use App\Domain\ServicePayload;
use Firebase\JWT\JWT;


class UserService extends AbstractDomainService implements ICrudService, IAuthService
{
    private InputValidator $validation;
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
        InputValidator     $validation,
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
        return $this->processAndSave($data, new User());
    }

    private function processAndSave(array $data, User $user): ServicePayload
    {
        if (!$this->validation->isValid($data, $user)) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }
        $user->setData($data);
        $field = $this->repository->getDuplicateField($user);
        if ($field !== null) {
            return $this->ServicePayload(ServicePayload::STATUS_DUPLICATE_ENTITY, ['field' => $field]);
        }

        if (!$this->schoolRepository->getById($user->getSchool()->getId())) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['school' => self::ENTITY_NOT_FOUND]);
        }

        if (!$this->addressRepository->getCityById($user->getCity()->getId())) {
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

        return $this->processAndSave($data, $user);
    }

    public function auth(array $data): ServicePayload
    {
        if (!isset($data['email'], $data['password'])) {
            return $this->ServicePayload(ServicePayload::STATUS_INVALID_INPUT);
        }
        $user = $this->repository->getByEmail($data['email']);
        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Usuario não existente']);
        }
        if (!$user->comparePassword($data['password'])) {
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
            'user' => $user->getId(),
            'type' => $user->getType(),

        ];

        return JWT::encode($token, KEY);
    }
}
