<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Address\AddressRepositoryInterface;
use App\Domain\School\SchoolRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterfaceInterface;

class UserRepository implements UserRepositoryInterfaceInterface
{
    private MysqlRepository $repository;
    private AddressRepositoryInterface $addressRepository;
    private SchoolRepositoryInterface $schoolRepository;

    public function __construct(
        MysqlRepository $mysqlRepository,
        AddressRepositoryInterface $addressRepository,
        SchoolRepositoryInterface $schoolRepository
    ) {
        $this->repository = $mysqlRepository;
        $this->addressRepository = $addressRepository;
        $this->schoolRepository = $schoolRepository;
    }

    public function save(User $user): bool
    {
        return $this->repository->saveEntity($user);
    }

    public function getById(int $id): ?User
    {
        $params = new EntityParams(User::class);
        $params->setFilters('id', (string)$id)
            ->setLimit(1);
        return $this->list($params)['result'][0] ?? null;
    }

    public function list(EntityParams $params): array
    {
        $payload = $this->repository->list($params);
        $fields = $params->getFields();
        $payload['result'] = array_map(
            function (User $user) use ($fields) {

                if ($fields === [] || in_array('city', $fields)) {
                    $user->__set('city', $this->addressRepository->getCityById($user->__get('city')->getId()));
                }
                if ($fields === [] || in_array('school', $fields)) {
                    $user->__set('school', $this->schoolRepository->getById($user->__get('school')->getId()));
                }
                return $user;
            },
            $payload['result']
        );
        return $payload;
    }

    public function getByEmail(string $email): ?User
    {
        $params = new EntityParams(User::class);
        $params->setFilters('email', $email)
            ->setLimit(1);
        return $this->list($params)['result'][0] ?? null;
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id, User::class);
    }

    public function getDuplicateField(User $user): ?string
    {
        return $this->repository->isDuplicateEntity($user, ['email', 'document']);
    }

    public function getError(): string
    {
        return $this->repository->getError();
    }
}
