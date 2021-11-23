<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Service\AbstractCrudService;
use App\Domain\AbstractEntity;
use App\Domain\Service\CrudServiceInterface;
use App\Domain\RepositoryInterface;
use App\Domain\Service\Payload;
use App\Domain\Service\Validator\InputValidator;

class SchoolService extends AbstractCrudService implements CrudServiceInterface
{

    protected string $class;
    private SchoolRepositoryInterface $repository;

    public function __construct(InputValidator $validation, SchoolRepositoryInterface $repository)
    {
        parent::__construct($validation);
        $this->repository = $repository;
        $this->class = School::class;
    }

    /** @noinspection PhpParamsInspection */
    protected function processEntity(AbstractEntity $entity): Payload
    {
        $field = $this->repository->getDuplicateField($entity);
        if ($field !== null) {
            return $this->servicePayload(Payload::STATUS_DUPLICATE_ENTITY, ['field' => $field]);
        }

        if (!$this->repository->save($entity)) {
            return $this->servicePayload(
                Payload::STATUS_ERROR,
                ['message' => self::SAVE_ERROR, 'description' => $this->repository->getError()]
            );
        }
        return $this->servicePayload(Payload::STATUS_SAVED, $entity);
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
}
