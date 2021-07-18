<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity;
use App\Domain\IRepository;
use App\Infrastructure\DB\DB;
use App\Domain\ServiceListParams;

abstract class MysqlRepository implements IRepository
{
    protected string $class;
    protected string $table;
    private DB $db;
    private string $lastError;
    private int $lastSaveId;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }


    protected function saveEntity(Entity $entity): bool
    {
        if (isset($entity->id) && $entity->id > 0) {
            if ($this->db->update($entity->id, $this->getTable(), $entity->jsonSerialize())) {
                $this->lastSaveId = $entity->id;
                return true;
            }
        } else {
            if ($this->db->insert($this->getTable(), $entity->jsonSerialize())) {
                $this->lastSaveId = $this->db->getLastInsertId();
                return true;
            }
        }
    
        $this->lastError = $this->db->getLastError();
        return false;
    }

    /*
    *@return  Entity|false
    */
    public function getById(int $id)
    {

        $params = new ServiceListParams($this->getClass());
        $params->setFilters('id', (string)$id)->setLimit(1);

        return $this->list($params)['result'][0] ?? false;
    }


    public function delete(int $id): bool
    {
        if (!($this->db->delete($this->getTable(), $id))) {
            $this->lastError = $this->db->getLastError();
            return false;
        }
        return true;
    }

    abstract protected function getClass(): string;

    public function getTable()
    {

        $class = explode('\\', $this->getClass());
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', end($class)));
    }

    public function list(ServiceListParams $params): array
    {
        $rows = $this->db->list($this->getTable(), $params->getFields(), $params->getFilters(), $params->getPage(), $params->getLimit());
        for ($i = 0; $i <= count($rows['result']) - 1; $i++) {
            $class = $this->getClass();
            $entity = new $class($rows['result'][$i]);
            $rows['result'][$i] = $entity;
        }

        return $rows;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }


    public function getLastSaveId(): int
    {
        return $this->lastSaveId;
    }
}
