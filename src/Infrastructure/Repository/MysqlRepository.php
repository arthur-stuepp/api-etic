<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use FaaPz\PDO\Clause\Conditional;
use FaaPz\PDO\Database;
use PDO;

abstract class MysqlRepository
{
    protected string $class;
    protected PDO $pdo;
    protected string $table;

    protected function __construct()
    {
        $this->pdo = new Database(MYSQL_DSN, MYSQL_USER, MYSQL_PWD);
        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    }

    protected function camel_to_snake(array $arr)
    {
        foreach ($arr as $key => $value) {
            $newKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            if ($newKey != $key) {
                $arr[$newKey] = $value;
                unset($arr[$key]);
            }

        }
        return $arr;
    }

    protected function insert(array $data){
        $insertStatement = $this->pdo->insert($this->camel_to_snake($data))
            ->into($this->table);
        $insertStatement->execute();

        return $this->pdo->lastInsertId();
    }


    protected function snakeToCamel(array $arr)
    {
        foreach ($arr as $key => $value) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if ($newKey != $key) {
                $arr[$newKey] = $value;
                unset($arr[$key]);
            }

        }
        return $arr;
//        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    protected function getByField($field, $value)
    {
        $selectStatement = $this->pdo->select()
            ->from($this->table)
            ->where(new Conditional($field, '=', $value));

        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if ($data) {
            return new $this->class($data);
        } else {
            return false;
        }
    }

    protected function delete(int $id)
    {
        $deleteStatement = $this->pdo->delete()
            ->from($this->table)
            ->where(new Conditional('id', "=", $id));


        return (bool)$affectedRows = $deleteStatement->execute()->rowCount();

    }


}
