<?php

declare(strict_types=1);

namespace App\Infrastructure\DB;

use PDO;
use Exception;
use PDOException;

class DB
{
    private PDO $db;
    private string $lastError;

    public function __construct()
    {

        try {
            $this->db = new PDO(MYSQL_DSN, MYSQL_USER, MYSQL_PWD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    /*
    *@return int:false
    */
    public function insert(string $table, array $data)
    {
        $data = $this->camel_to_snake($data);
        try {
            $fields  = implode(',', array_keys($data));
            $values = implode(',:', array_keys($data));
            $stmt = $this->db->prepare('INSERT INTO ' . $table . '(' . $fields . ')  VALUES (' . $values . ')');
            foreach ($data as $key => $value) {
                $stmt->bindParam(':' . $key, $value);
            }
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    public function delete(string $table, $id, string $field = 'id'): bool
    {
        try {

            $stmt = $this->db->prepare('DELETE FROM ' . $table . 'WHERE ' . $field . ':id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    public function list(string $table, array $fields = [], array $filters = [], int $page = 1, int $limit = 50)
    {
        

        try {
            $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . $this->getFields($fields) . ' FROM ' . $table .  $this->getFilters($filters) . $this->getLimit($page, $limit);
            $stmt = $this->db->prepare($sql);
            if ($filters !== []) {
                foreach ($filters as $key => $value) {
                    $stmt->bindParam(':' . $key, $value);
                }
            }

            $calcRows = $this->db->prepare('SELECT FOUND_ROWS()');
            $stmt->execute();
            $calcRows->execute();

            return [
                'total' => $calcRows->fetch()['FOUND_ROWS()'],
                'result' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {

            $this->lastError = $e->getMessage();

            return ['result' => []];
        }
    }

    private function getLimit(int $page, int $limit): string
    {

        $offset  =  ($page - 1) * $limit;

        return   ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    }
    private function getFilters(array $filters): string
    {
        $filterSql = '';
        if ($filters !== []) {
            $filterSql = ' WHERE ';
            foreach ($filters as $key => $value) {
                $filterSql .= $key . ' = :' . $key . ' AND ';
            }
            $filterSql = substr($filterSql, 0, -4);
        }
        return $filterSql;
    }

    private function getFields(array $fields): string
    {
        $fieldSql = '*';
        if ($fields !== []) {

            $fieldSql = implode(',:', array_values($fields));
        }
        return $fieldSql;
    }


    public function getLastError(): string
    {
        return $this->lastError;
    }

    protected function camel_to_snake(array $arr): array
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

    protected function snakeToCamel(array $arr): array
    {
        foreach ($arr as $key => $value) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if ($newKey != $key) {
                $arr[$newKey] = $value;
                unset($arr[$key]);
            }
        }
        return $arr;
    }
}
