<?php

declare(strict_types=1);

namespace App\Infrastructure\DB;

use App\Infrastructure\DatabaseException\DatabaseException;
use Exception;
use PDO;
use PDOException;

class DB
{
    private PDO $db;
    private string $error;
    private array $tables;


    /**
     * @throws Exception
     */
    public function __construct(string $dsn = MYSQL_DSN, string $user = MYSQL_USER, string $password = MYSQL_PWD)
    {

        try {
            $this->db = new PDO($dsn, $user, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->fetchAllTables();
        } catch (PDOException $e) {
            throw new DatabaseException('Erro ao conectar com o banco', $e->getMessage());

        }
    }

    private function fetchAllTables()
    {
        $sql = 'SHOW TABLES ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $this->tables = array_map(function ($tables) {
            return array_values($tables)[0];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));

    }

    public function insert(string $table, array $data): bool
    {
        if (!$this->validateTable($table)) {
            $this->error = 'Tabela :' . $table . ' não encontrada';
            return false;
        }
        $data = $this->camel_to_snake($data);
        $fields = array_flip($this->fetchAllFields($table));

        $data = (array_intersect_key($data, $fields));
        try {
            $fields = implode(',', array_keys($data));
            $values = ':' . implode(' , :', array_keys($data));
            $sql = 'INSERT INTO ' . $table . '(' . $fields . ')  VALUES (' . $values . ')';
            $sqlDebug = $sql;
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $value) {
                if ($value === false) {
                    $value = 0;
                }
                $sqlDebug = str_replace(':' . $key, $value, $sqlDebug);
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();


            return false;
        }
    }

    private function validateTable(string $table): bool
    {
        return in_array($table, $this->tables);
    }

    private function camel_to_snake(array $array): array
    {
        foreach ($array as $key => $value) {
            $newKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            if ($newKey != $key) {
                $array[$newKey] = $value;
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function fetchAllFields($table): array

    {
        $sql = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA=(SELECT DATABASE()) AND TABLE_NAME =:table';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['table' => $table]);

        return array_map(function ($row) {
            return $row['COLUMN_NAME'];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));

    }

    public function update(int $id, string $table, array $data): bool
    {
        if (!$this->validateTable($table)) {
            $this->error = 'Tabela :' . $table . ' não encontrada';
            return false;
        }
        $data = $this->camel_to_snake($data);
        unset($data['id']);
        try {
            $fields = '';
            foreach ($data as $key => $value) {
                $fields .= $key . ' = :' . $key . ', ';
            }
            $fields = rtrim($fields, ', ');
            $sql = 'UPDATE ' . $table . ' SET ' . $fields . ' WHERE id = :id';
            $sqlDebug = $sql;
            $stmt = $this->db->prepare($sql);
            $data['id'] = $id;
            foreach ($data as $key => $value) {
                if ($value === false) {
                    $value = 0;
                }

                if (is_null($value)) {
                    $stmt->bindValue(':' . $key, 'null', PDO::PARAM_NULL);
                    $sqlDebug = str_replace(':' . $key, 'null', $sqlDebug);
                } else {
                    $sqlDebug = str_replace(':' . $key, '\'' . $value . '\'', $sqlDebug);
                    $stmt->bindValue(':' . $key, $value);
                }
            }
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function delete(string $table, $id, string $field = 'id'): bool
    {
        if (!$this->validateTable($table)) {
            $this->error = 'Tabela :' . $table . ' não encontrada';
            return false;
        }
        try {
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $field . '= :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function list(string $table, array $fields = [], array $filters = [], int $page = 1, int $limit = 50): array
    {
        if (isset($filters['name']) && isset($filters['search'])) {
            unset($filters['name']);
        }
        $filters = $this->camel_to_snake($filters);
        try {
            $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . $this->getFields($fields) . ' FROM ' . $table . $this->getFilters($filters) . $this->getLimit($page, $limit);
            $stmt = $this->db->prepare($sql);
            $sqlDebug = $sql;
            if ($filters !== []) {
                foreach ($filters as $key => $value) {
                    if ($key === 'search') {
                        $stmt->bindValue(':name', '%' . $value . '%');
                        $sqlDebug = str_replace(':name', $value, $sqlDebug);
                    } else {

                        $stmt->bindValue(':' . $key, $value);
                        $sqlDebug = str_replace(':' . $key, $value, $sqlDebug);
                    }
                }
            }

            $calcRows = $this->db->prepare('SELECT FOUND_ROWS()');
            $stmt->execute();
            $calcRows->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i <= count($rows) - 1; $i++) {
                $rows[$i] = $this->snakeToCamel($rows[$i]);
            }

            return [
                'total' => $calcRows->fetch()['FOUND_ROWS()'],
                'result' => $rows
            ];
        } catch (Exception $e) {
            $this->error = $e->getMessage();

            return ['total' => 0, 'result' => []];
        }
    }

    private function getFields(array $fields): string
    {
        if ($fields === []) {
            return '*';
        }

        return implode(',', array_values($this->camel_to_snake($fields)));
    }

    private function getFilters(array $filters): string
    {
        $filterSql = '';
        if ($filters !== []) {
            $filterSql = ' WHERE ';
            foreach ($filters as $key => $value) {
                if ($key === 'search') {
                    $filterSql .= 'name like :name AND ';
                } else {
                    $filterSql .= $key . ' = :' . $key . ' AND ';
                }
            }
            $filterSql = substr($filterSql, 0, -4);
        }
        return $filterSql;
    }

    private function getLimit(int $page, int $limit): string
    {

        $offset = ($page - 1) * $limit;
        $limit = $limit === 0 ? 1 : $limit;
        return ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    }

    private function snakeToCamel(array $array): array
    {
        foreach ($array as $key => $value) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if ($newKey != $key) {
                $array[$newKey] = $value;
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getLastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}
