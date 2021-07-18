<?php

namespace App\Domain;


class ServicePayload
{
    public const STATUS_VALID = 200;
    public const STATUS_FOUND = 200;
    public const STATUS_CREATED = 201;
    public const STATUS_UPDATED = 201;
    public const STATUS_DELETED = 202;
    public const STATUS_FORBIDDEN = 403;
    protected const STATUS_NOT_CREATED = 422;
    public const STATUS_NOT_DELETED = 422;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_NOT_UPDATED = 422;
    public const STATUS_NOT_VALID = 422;
    public const STATUS_ERROR = 500;

    /**
     * @var array|string
     */
    private $result;

    private int $status;

    public function __construct(int $status, $result = [])
    {
        $this->status = $status;
        $this->result = $result;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResult()
    {
        if (DEBUG === false && isset($this->result['description'])) {
            unset($this->result['description']);
        }
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
