<?php

declare(strict_types=1);

namespace App\Domain\Services;

abstract class ApplicationService
{

    protected function ServicePayload(int $status, $result = []): ServicePayload
    {
        return new ServicePayload($status, $result);
    }

    protected function params(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }
}
