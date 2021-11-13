<?php

declare(strict_types=1);

namespace App\Domain\General;

class ServiceListParams
{

    private array $fields = [];
    private array $filters = [];
    private int $page = 1;
    private int $limit = 15;
    private string $class;

    public function __construct(string $class, array $data = [])
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

    public function setFields($fields): self
    {
        $fields = explode(',', $fields);
        foreach ($fields as $field) {
            if (property_exists($this->class, $field)) {
                $this->fields[] = $field;
            }
        }
        return $this;
    }


    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(string $field, string $filter): self
    {

        if (property_exists($this->class, $field) || $field === 'search') {
            $this->filters[$field] = $filter;
        }

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage($page): self
    {
        if (is_numeric($page)) {
            $this->page = (int)$page;
        }

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit($limit): self
    {
        if (is_numeric($limit)) {
            $this->limit = (int)$limit;
        }

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
}
