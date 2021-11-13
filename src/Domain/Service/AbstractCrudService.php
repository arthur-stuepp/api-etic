<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\AbstractEntity;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\DomainFieldException;
use App\Domain\RepositoryInterface;
use App\Domain\Validator\InputValidator;
use App\Infrastructure\Repository\EntityParams;
use Exception;

abstract class AbstractCrudService extends AbstractDomainService implements CrudServiceInterface
{

    protected InputValidator $validator;

    public function __construct(InputValidator $validator)
    {
        $this->validator = $validator;
    }

    public function create(array $data): ServicePayload
    {
        return $this->validateInput($data);
    }

    /** @noinspection PhpRedundantCatchClauseInspection */
    private function validateInput(array $data): ServicePayload
    {
        if (!$this->validator->isValid($data, new $this->class())) {
            return $this->servicePayload(
                ServicePayload::STATUS_INVALID_INPUT,
                ['fields' => $this->validator->getMessages()]
            );
        }
        try {
            $entity = new $this->class($data);
        } catch (DomainFieldException $e) {
            return $this->servicePayload(
                ServicePayload::STATUS_INVALID_ENTITY,
                ['fields' => [$e->getField() => $e->getMessage()]]
            );
        } catch (DomainException $e) {
            return $this->servicePayload($e->getCode(), ['message' => $e->getMessage()]);
        } catch (Exception $e) {
            return $this->servicePayload(
                ServicePayload::STATUS_ERROR,
                [
                    'message' => 'Ocorreu um erro interno ao processar sua solicitação.',
                    'description' => $e->getMessage()
                ]
            );
        }
        return $this->processEntity($entity);
    }

    abstract protected function processEntity(AbstractEntity $entity): ServicePayload;

    public function update(int $id, array $data): ServicePayload
    {
        $entity = $this->getRepository()->getById($id);
        if ($entity === null) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        $data['id'] = $id;

        return $this->validateInput($data);
    }

    abstract protected function getRepository(): RepositoryInterface;

    public function list(array $queryParams): ServicePayload
    {
        $params = new EntityParams($this->class, $queryParams);
        return $this->servicePayload(ServicePayload::STATUS_FOUND, $this->getRepository()->list($params));
    }

    public function read(int $id): ServicePayload
    {
        $entity = $this->getRepository()->getById($id);
        if ($entity === null) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }

        return $this->servicePayload(ServicePayload::STATUS_FOUND, $entity);
    }

    public function delete(int $id): ServicePayload
    {
        if (!$this->getRepository()->getById($id)) {
            return $this->servicePayload(ServicePayload::STATUS_NOT_FOUND);
        }
        if (!$this->getRepository()->delete($id)) {
            if (strpos($this->getRepository()->getError(), '1451 Cannot delete or update a parent row') !== false) {
                return $this->servicePayload(ServicePayload::STATUS_NOT_DELETED, [
                    'message' => 'Não é possivel deletar esse registro porque está associado a outros registros.',
                    'description' => $this->getRepository()->getError()
                ]);
            }
            return $this->servicePayload(ServicePayload::STATUS_ERROR, [
                'message' => 'Não foi possivel deletar esse registro',
                'description' => $this->getRepository()->getError()
            ]);
        }

        return $this->servicePayload(ServicePayload::STATUS_DELETED, ['message' => 'Deletado com sucesso']);
    }
}
