<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\General\ServiceListParams;
use App\Domain\School\School;
use App\Domain\School\SchoolRepositoryInterface;

class SchoolRepository implements SchoolRepositoryInterface
{

    private MysqlRepository $repository;

    public function __construct(MysqlRepository $mysqlRepository)
    {
        $this->repository = $mysqlRepository;
    }

    public function save(School $school): bool
    {
        return $this->repository->saveEntity($school);
    }

    public function getById(int $id): ?School
    {
        $params = new ServiceListParams(School::class);
        $params->setFilters('id', (string)$id)
            ->setLimit(1);
        return $this->repository->list($params)['result'][0] ?? null;
    }

    public function list(ServiceListParams $params): array
    {
        return $this->repository->list($params);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id, School::class);
    }

    public function getError(): string
    {
        return $this->repository->getError();
    }

    public function getDuplicateField(School $entity): ?string
    {
        return $this->repository->isDuplicateEntity($entity, ['name']);
    }
}
