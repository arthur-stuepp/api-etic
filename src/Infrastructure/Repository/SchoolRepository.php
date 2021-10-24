<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\School\ISchoolRepository;
use App\Domain\School\School;
use App\Domain\Services\ServiceListParams;

;

class SchoolRepository implements ISchoolRepository
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

    public function getById(int $id)
    {
        $params = new ServiceListParams(School::class);
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
        return $this->repository->delete($id, School::class);
    }

}
