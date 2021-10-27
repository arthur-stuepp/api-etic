<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity;
use App\Domain\General\Interfaces\IHasUniquiProperties;
use App\Domain\General\ServiceListParams;
use App\Infrastructure\DB\DB;

class MysqlRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }


    public function saveEntity(Entity $entity): bool
    {
        $class = get_class($entity);
        if (isset($entity->id) && $entity->id > 0) {
            if ($this->db->update($entity->id, $this->getTable($class), get_object_vars($entity))) {

                return true;
            }
        } else {
            if ($this->db->insert($this->getTable($class), get_object_vars($entity))) {
                $entity->id = $this->db->getLastInsertId();
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

    /**
     * @param int $id
     * @param string $table
     * @return false|Entity
     */
    public function getById(int $id, string $table)
    {

        $params = new ServiceListParams($table);
        $params->setFilters('id', (string)$id)->setLimit(1);

        return $this->list($params)['result'][0] ?? false;
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
        for ($i = 0; $i <= count($rows['result']) - 1; $i++) {
            $class = $params->getClass();
            $entity = new $class($rows['result'][$i]);
            $rows['result'][$i] = $entity;
        }

        return $rows;
    }

    public function delete(int $id, string $table): bool
    {
        if (!($this->db->delete($table, $id))) {
            return false;
        }
        return true;
    }

    public function getError(): string
    {
        return $this->db->getError();
    }

    public function isDuplicateEntity(IHasUniquiProperties $entity): ?string
    {
        $fields = $entity->getFields();
        foreach ($fields as $field) {
            $params = new ServiceListParams(get_class($entity));
            $params->setFilters($field, $entity->$field);
            $params->setFields('id');
            $payload = $this->list($params);
            if ($payload['total'] > 0) {
                if ($entity->getId() === 0) {
                    return $field;
                }
                if ($entity->getId() !== $payload['result'][0]->id) {
                    return $field;
                }
            }

        }
        return null;
    }


}
