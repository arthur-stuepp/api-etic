<?php

declare(strict_types=1);

namespace App\Domain\Service;

class ServiceListParams {

    private $fields = [];
    private $filters = [];
    private $orderBy = [];
    private $groupBy = [];
    private $page = 1;
    private $offset = 15;
    private $class;

    public function __construct(string $class, array $data) {
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

    /**
     * @return array
     */
    public function getFields(): array {
        return $this->fields;
    }

    public function setFields($fields): void {
        $fields = explode(',', $fields);
        foreach ($fields as $field) {
            if (property_exists($this->class, $field)) {
                $this->fields[] = $field;
            }
        }
    }

    /**
     * @return array
     */
    public function getFilters(): array {
        return $this->filters;
    }

    public function setFilters(string $field, string $filter): void {
        if (property_exists($this->class, $field)) {
            $this->filters[$field] = $filter;
        }
    }

    public function getOrderBy(): array {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void {
        $ordersBy = explode(',', $orderBy);
        foreach ($ordersBy as $orderBy) {
            if (property_exists($this->class, $orderBy)) {
                $this->fields[] = $orderBy;
            }
        }
    }

    public function getGroupBy(): array {
        return $this->groupBy;
    }

    public function setGroupBy(string $groupBy): void {
        $groupsBy = explode(',', $groupBy);
        foreach ($groupsBy as $groupBy) {
            if (property_exists($this->class, $groupBy)) {
                $this->fields[] = $groupBy;
            }
        }
    }

    public function getPage(): int {
        return $this->page;
    }

    public function setPage($page): void {
        if (is_numeric($page)) {
            $this->page = (int) $page;
        }
    }

    public function getOffset(): int {
        return $this->offset;
    }

    public function setOffset($offset): void {
        if (is_numeric($offset)) {
            $this->offset = (int) $offset;
        }
    }

}