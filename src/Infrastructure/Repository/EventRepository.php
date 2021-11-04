<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Event\Event;
use App\Domain\Event\EventRepositoryInterface;
use App\Domain\Event\EventUser;
use App\Domain\General\ServiceListParams;
use ArrayObject;
use Exception;
use ReflectionProperty;

class EventRepository implements EventRepositoryInterface
{
    private MysqlRepository $repository;

    public function __construct(MysqlRepository $mysqlRepository)
    {
        $this->repository = $mysqlRepository;
    }

    public function save(Event $event): bool
    {
        try {
            $this->repository->beginTransaction();
            $result = $this->repository->saveEntity($event);
            $params = new ServiceListParams(EventUser::class);
            $params->setFilters('event', ((string)$event->getId()))->setLimit(0);
            $userEvents = $this->list($params)['result'];
            $toDelete = array_filter($userEvents, function (EventUser $eventUser) use ($event) {
                return !$event->hasUser($eventUser->getUser()->getId());

            });
            foreach ($event->getUsers() as $eventUser) {
                if (!$this->repository->saveEntity($eventUser)) {
                    throw new Exception();
                }
            }
            foreach ($toDelete as $eventUser) {
                if (!$this->repository->delete($eventUser->getId(), EventUser::class)) {
                    throw new Exception();
                }
            }
        } catch (Exception $exception) {
            $this->repository->rollBackTransaction();
            return false;
        }
        if ($result === true) {
            $this->repository->commitTransaction();
            return true;
        }
        $this->repository->rollBackTransaction();
        return false;
    }

    public function list(ServiceListParams $params): array
    {
        return $this->repository->list($params);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function getById(int $id)
    {
        $params = new ServiceListParams(Event::class);
        $params->setFilters('id', (string)$id)
            ->setLimit(1);
        /** @var Event|false $event */
        $event = $this->repository->list($params)['result'][0] ?? false;

        if ($event !== false) {
            $rp = new ReflectionProperty($event, 'users');
            $rp->setAccessible(true);
            $params = new ServiceListParams(EventUser::class);
            $params->setFilters('event', (string)$event->getId())->setLimit(0);
            $eventUsers = $this->repository->list($params)['result'];
            $arrayObject = new ArrayObject();
            foreach ($eventUsers as $eventUser) {
                $arrayObject[$eventUser->getUser()->getId()] = $eventUser;
            }
            $rp->setValue($event, $arrayObject);

        }
        return $event;
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
