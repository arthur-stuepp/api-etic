<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Event\Event;
use App\Domain\Event\EventRepositoryInterface;
use App\Domain\School\School;
use App\Domain\General\ServiceListParams;

class EventRepository  implements EventRepositoryInterface
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $mysqlRepository)
    {
        $this->repository = $mysqlRepository;
    }

    public function save(Event $event): bool
    {
        return $this->repository->saveEntity($event);
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
        return $this->repository->delete($id, Event::class);
    }

    public function getError(): string
    {
        return $this->repository->getError();
    }
}
