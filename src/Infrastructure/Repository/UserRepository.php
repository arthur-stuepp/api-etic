<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\IHasUniquiProperties;
use App\Domain\Services\ServiceListParams;
use App\Domain\User\IUserRepository;
use App\Domain\User\User;

class UserRepository implements IUserRepository
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $mysqlRepository)
    {
        $this->repository = $mysqlRepository;
    }

    public function save(User $user): bool
    {
        return $this->repository->saveEntity($user);
    }

    public function getById(int $id)
    {
        $params = new ServiceListParams(User::class);
        $params->setFilters('id', (string)$id)
            ->setLimit(1);
        return $this->repository->list($params)['entities'][0] ?? false;
    }

    public function list(ServiceListParams $params): array
    {
        return $this->repository->list($params);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id, User::class);
    }

    public function getError(): string
    {
        return $this->repository->getError();
    }

    public function getDuplicateField(IHasUniquiProperties $properties):?string
    {
        return $this->repository->isDuplicateEntity($properties, User::class);
    }
}

