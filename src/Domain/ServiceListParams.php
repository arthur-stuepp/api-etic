<?php

declare(strict_types=1);

namespace App\Domain;

class ServiceListParams
{

    private array $fields = [];
    private array  $filters = [];
    private string $orderBy = '';
    private string $groupBy = '';
    private int $page = 1;
    private int $offset = 15;
    private string $class;

    public function __construct(string $class, array $data)
    {
        $this->class = $class;
        foreach ($data as $key => $val) {
            if (property_exists(__CLASS__, $key)) {
                $method = 'set' . $key;
                $this->$method($val);
            } else {
                $this->setFilters($key, $val);
            }
        }
    }


    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields($fields): void
    {
        $fields = explode(',', $fields);
        foreach ($fields as $field) {
            if (property_exists($this->class, $field)) {
                $this->fields[] = $field;
            }
        }
    }


    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(string $field, string $filter): void
    {
        if (property_exists($this->class, $field)) {
            $this->filters[$field] = $filter;
        }
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }

    public function setGroupBy(string $groupBy): void
    {
        $this->groupBy = $groupBy;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage($page): void
    {
        if (is_numeric($page)) {
            $this->page = (int)$page;
        }
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset($offset): void
    {
        if (is_numeric($offset)) {
            $this->offset = (int)$offset;
        }
    }

}