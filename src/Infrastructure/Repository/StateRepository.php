<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Services\ServiceListParams;
use App\Domain\State\IStateRepository;
use App\Domain\State\State;

class StateRepository  implements IStateRepository
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getById(int $id)
    {
        $params = new ServiceListParams(State::class);
        $params->setFilters('id', (string)$id)->setLimit(1);
        return $this->list($params)['entities'][0] ?? false;
    }

    public function list(ServiceListParams $params): array
    {
        $result = $this->repository->list($params);
        $result['entities'] = array_map(function ($row) {
            return new State($row);
        }, $result['entities']);
        return $result;
    }

}
