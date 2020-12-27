<?php

namespace App\Domain;


class ServicePayload
{
    /** User input was valid. */
    public const STATUS_VALID = 200;

    /** A query successfully returned results. */
    public const STATUS_FOUND = 200;

    /** A creation command succeeded. */
    public const STATUS_CREATED = 201;

    /** An update command succeeded. */
    public const STATUS_UPDATED = 201;

    /** A deletion command succeeded. */
    public const STATUS_DELETED = 202;

    public const STATUS_FORBIDDEN = 403;

    /** A creation command failed. */
    protected const STATUS_NOT_CREATED = 422;

    /** A deletion command failed. */
    public const STATUS_NOT_DELETED = 422;

    /** A query failed to return results. */
    public const STATUS_NOT_FOUND = 404;

    /** An update command failed. */
    public const STATUS_NOT_UPDATED = 422;

    /** User input was not valid. */
    public const STATUS_NOT_VALID = 422;

    /** There was a major error of some sort. */
    public const STATUS_ERROR = 500;

    /**
     * @var array|string|Entity
     */
    private $result;

    private int $status;

    public function __construct(string $status, $result = [])
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
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
