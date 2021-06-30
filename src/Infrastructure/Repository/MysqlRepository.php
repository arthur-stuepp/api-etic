<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity;
use App\Infrastructure\DB\DB;
use App\Domain\ServiceListParams;

abstract class MysqlRepository
{
    protected string $class;
    protected string $table;
    private DB $db;
    private string $lastError;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }


    /*
     *@return int|false
     */
    public function create(Entity $entity)
    {
        return $this->processAndReturn($this->db->insert($this->getTable(), $entity->jsonSerialize()));
    }
    /*
     *@return int|false
     */
    public function update(Entity $entity)
    {

        return $this->processAndReturn($this->db->insert($this->getTable(), $entity->jsonSerialize()));
    }


    /*
    *@return  Entity|false
    */
    public function getById(int $id)
    {
        $params = new ServiceListParams($this->getClass());
        $params->setFilters('id', (string)$id)->setLimit(1);
     
        return $this->list($params);
    }


    public function delete(int $id): bool
    {

        return $this->processAndReturn($this->db->delete($this->getTable(), $id));
    }

    abstract protected function getClass(): string;

    public function getTable()
    {
        $class = explode('\\', $this->getClass());
    
        return  end($class);
    }

    public function list(ServiceListParams $params): array
    {
        $rows = $this->db->list($this->getTable(), $params->getFields(), $params->getFilters(), $params->getPage(), $params->getLimit());
        
        for ($i = 0; $i < count($rows['result']) - 1; $i++) {
            $class = $this->getClass();
            $entity = new $class($rows['result'][$i]);
            $rows['result'][$i] = $entity;
        }
        return $rows;
    }

    private function processAndReturn($return)
    {
        $this->lastError = '';
        if ($return === false) {
            $this->lastError = $this->db->getLastError();
        }

        return $return;
    }
    public function getLastError()
    {
        return $this->lastError;
    }
}
