<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractDomainService;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\CrudServiceInterface;
use App\Domain\General\ServiceListParams;
use App\Domain\General\Traits\TraitDeleteService;
use App\Domain\General\Traits\TraitListService;
use App\Domain\General\Traits\TraitReadService;
use App\Domain\General\Validator\InputValidator;
use App\Domain\School\SchoolRepositoryInterface;
use App\Domain\ServicePayload;
use Firebase\JWT\JWT;


class UserService extends AbstractDomainService implements CrudServiceInterface, AuthServiceInterface
{
    private InputValidator $validation;
    private UserRepositoryInterface $repository;
    private SchoolRepositoryInterface $schoolRepository;
    private AddressRepositoryInterface $addressRepository;
    private ServiceListParams $params;
    private string $class;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;

    public function __construct
    (
        InputValidator             $validation,
        UserRepositoryInterface    $repository,
        SchoolRepositoryInterface  $schoolRepository,
        AddressRepositoryInterface $addressRepository
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
        $data['type'] = $data['type'] ?? User::TYPE_USER;
        if (!$this->validation->isValid($data, new User())) {
            return $this->servicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }
        $user = new User($data);

        return $this->processAndSave($user);
    }

    private function processAndSave(User $user): ServicePayload
    {

        $field = $this->repository->getDuplicateField($user);
        if ($field !== null) {
            return $this->servicePayload(ServicePayload::STATUS_DUPLICATE_ENTITY, ['field' => $field]);
        }

        if (!$this->schoolRepository->getById($user->getSchool()->getId())) {
            return $this->servicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['school' => self::NOT_FOUND]);
        }

        if (!$this->addressRepository->getCityById($user->getCity()->getId())) {
            return $this->servicePayload(ServicePayload::STATUS_INVALID_ENTITY, ['city' => self::NOT_FOUND]);
        }

        if (!$this->repository->save($user)) {
            return $this->servicePayload(ServicePayload::STATUS_ERROR, ['description' => $this->repository->getError()]);
        }

        return $this->servicePayload(ServicePayload::STATUS_SAVED, $this->repository->getById($user->getId()));
    }

    public function update(int $id, array $data): ServicePayload
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        $data['id'] = $id;
        if (!$this->validation->isValid($data, new User())) {
            return $this->servicePayload(ServicePayload::STATUS_INVALID_INPUT, ['fields' => $this->validation->getMessages()]);
        }
        $user = new User($data);

        return $this->processAndSave($user);
    }

    public function auth(array $data): ServicePayload
    {
        if (!isset($data['email'], $data['password'])) {
            return $this->servicePayload(ServicePayload::STATUS_INVALID_INPUT);
        }
        $user = $this->repository->getByEmail($data['email']);

        if (!$user) {
            return $this->servicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Email ou senha Incorretos']);
        }
        if (!$user->comparePassword($data['password'])) {
            return $this->servicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Email ou senha Incorretos']);
        }
        $token = $this->tokenGenerate($user);

        return $this->servicePayload(ServicePayload::STATUS_VALID, ['token' => $token, 'user' => $user]);
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
