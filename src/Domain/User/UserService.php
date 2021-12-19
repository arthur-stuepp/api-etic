<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractEntity;
use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\RepositoryInterface;
use App\Domain\School\SchoolRepositoryInterface;
use App\Domain\Service\AbstractCrudService;
use App\Domain\Service\Payload;
use App\Domain\Service\Validator\InputValidator;
use App\Infrastructure\Repository\EntityParams;
use Firebase\JWT\JWT;

class UserService extends AbstractCrudService implements AuthServiceInterface
{
    protected UserRepositoryInterface $repository;
    protected EntityParams $params;
    protected string $class;
    private SchoolRepositoryInterface $schoolRepository;
    private AddressRepositoryInterface $addressRepository;

    public function __construct(
        InputValidator $validation,
        UserRepositoryInterface $repository,
        SchoolRepositoryInterface $schoolRepository,
        AddressRepositoryInterface $addressRepository
    ) {
        parent::__construct($validation);
        $this->repository = $repository;
        $this->schoolRepository = $schoolRepository;
        $this->addressRepository = $addressRepository;
        $this->class = User::class;
    }

    public function auth(array $data): Payload
    {
        if (!isset($data['email'], $data['password'])) {
            return $this->servicePayload(Payload::STATUS_INVALID_ENTITY);
        }
        $user = $this->repository->getByEmail($data['email']);

        if (!$user) {
            return $this->servicePayload(Payload::STATUS_FORBIDDEN, ['message' => 'Email ou senha Incorretos']);
        }
        if (!$user->comparePassword($data['password'])) {
            return $this->servicePayload(Payload::STATUS_FORBIDDEN, ['message' => 'Email ou senha Incorretos']);
        }
        $token = $this->tokenGenerate($user);

        return $this->servicePayload(Payload::STATUS_VALID, ['token' => $token, 'user' => $user]);
    }

    private function tokenGenerate(User $user): string
    {

        $token = [
            'iss' => 'https://' . $_SERVER['HTTP_HOST'],
            'iat' => time(),
            'exp' => strtotime('+1 day', time()),
            'user' => $user->getId(),
            'type' => $user->__get('type'),

        ];

        return JWT::encode($token, KEY);
    }

    /** @noinspection PhpParamsInspection */
    protected function processEntity(AbstractEntity $entity): Payload
    {

        $field = $this->repository->getDuplicateField($entity);
        if ($field !== null) {
            return $this->servicePayload(Payload::STATUS_DUPLICATE_ENTITY, ['field' => $field]);
        }

        if (!$this->schoolRepository->getById($entity->__get('school')->getId())) {
            return $this->servicePayload(Payload::STATUS_INVALID_ENTITY, ['school' => self::NOT_FOUND]);
        }

        if (!$this->addressRepository->getCityById($entity->__get('city')->getId())) {
            return $this->servicePayload(Payload::STATUS_INVALID_ENTITY, ['city' => self::NOT_FOUND]);
        }

        if (!$this->repository->save($entity)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }

        return $this->servicePayload(Payload::STATUS_SAVED, $this->repository->getById($entity->getId()));
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
}
