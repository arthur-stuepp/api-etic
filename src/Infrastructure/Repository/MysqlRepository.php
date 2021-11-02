<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\AbstractEntity;
use App\Domain\General\ServiceListParams;
use App\Domain\UniquiPropertiesInterface;
use App\Infrastructure\DB\DB;
use ReflectionClass;

class MysqlRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function saveEntity(AbstractEntity $entity): bool
    {
        $class = get_class($entity);
        $reflect = new ReflectionClass($entity);
        $props = $reflect->getProperties();

        $data = [];
        foreach ($props as $prop) {
            $prop->setAccessible(true);
            if ($prop->isInitialized($entity)) {
                $data[$prop->getName()] = $prop->getValue($entity);
            }

        }

        if ($entity->getId() !== 0) {
            if ($this->db->update($entity->getId(), $this->getTable($class), $data)) {

                return true;
            }
        } else {
            if ($this->db->insert($this->getTable($class), $data)) {
                $entity->setId($this->db->getLastInsertId());
                return true;
            }
        }


        return false;
    }


    private function getTable(string $class): string
    {
        $class = explode('\\', $class);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', end($class)));
    }

    public function delete(int $id, string $class): bool
    {
        if (!($this->db->delete($this->getTable($class), $id))) {
            return false;
        }
        return true;
    }

    public function getError(): string
    {
        return $this->db->getError();
    }

    public function isDuplicateEntity(UniquiPropertiesInterface $entity): ?string
    {
        $fields = $entity->getProperties();
        foreach ($fields as $field => $value) {
            $params = new ServiceListParams(get_class($entity));
            $params->setFilters($field, (string)$value);
            $params->setFields('id');
            $payload = $this->list($params);
            if ($payload['total'] > 0) {
                if ($entity->getId() === 0) {
                    return $field;
                }
                if ($entity->getId() !== $payload['result'][0]->getId()) {
                    return $field;
                }
            }

        }
        return null;
    }


    public function list(ServiceListParams $params): array
    {
        $rows = $this->db->list(
            $this->getTable($params->getClass()),
            $params->getFields(),
            $params->getFilters(),
            $params->getPage(),
            $params->getLimit()
        );
        for ($i = 0; $i < count($rows['result']); $i++) {
            $class = $params->getClass();
            $entity = new $class($rows['result'][$i]);
            $fields = $params->getFields();
            if ($fields !== []) {
                $diffs = array_diff_key($entity->jsonSerialize(), array_flip($params->getFields()));
                foreach ($diffs as $key => $value) {
                    if (!isset($fields[$key])) {
                        unset($entity->$key);
                    }
                }
            }

            $rows['result'][$i] = $entity;
        }

        return $rows;
    }


}
