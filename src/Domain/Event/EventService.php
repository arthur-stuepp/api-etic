<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AbstractEntity;
use App\Domain\Event\User\EventUser;
use App\Domain\RepositoryInterface;
use App\Domain\Service\AbstractCrudService;
use App\Domain\Service\Payload;
use App\Domain\Service\Validator\InputValidator;
use App\Infrastructure\Repository\EntityParams;

class EventService extends AbstractCrudService
{
    protected string $class;
    private EventRepositoryInterface $repository;

    public function __construct(
        InputValidator $validator,
        EventRepositoryInterface $repository
    ) {
        parent::__construct($validator);
        $this->repository = $repository;
        $this->class = Event::class;
    }

    public function list(array $queryParams): Payload
    {
        $params = new EntityParams($this->class, $queryParams);
        if (isset($params['user'])) {
            $params->setJoin(EventUser::class, 'user', $params['user']);
        }
        return $this->servicePayload(Payload::STATUS_FOUND, $this->repository->list($params));
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    protected function processEntity(AbstractEntity $entity): Payload
    {
        /** @noinspection PhpParamsInspection */
        if (!$this->repository->save($entity)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_VALID, $entity);
    }
}
