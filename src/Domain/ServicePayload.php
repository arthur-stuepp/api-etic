<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\General\Interfaces\IEntity;

class ServicePayload
{
    public const STATUS_VALID = 200;
    public const STATUS_FOUND = 200;
    public const STATUS_SAVED = 201;
    public const STATUS_DELETED = 202;
    public const STATUS_INVALID_INPUT = 400;
    public const STATUS_FORBIDDEN = 403;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_NOT_DELETED = 409;
    public const STATUS_DUPLICATE_ENTITY = 409;
    public const STATUS_INVALID_ENTITY = 422;
    public const STATUS_ERROR = 500;

    /**
     * @var array|string|IEntity
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


    public function getResult()
    {
        if (DEBUG === false && isset($this->result['description'])) {
            unset($this->result['description']);
        }
        return $this->result;
    }

}
