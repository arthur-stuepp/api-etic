<?php

declare(strict_types=1);

namespace App\Domain;

abstract class ApplicationService
{

    protected function ServicePayload(int $status, $result = []): ServicePayload
    {
        return new ServicePayload($status, $result);
    }
}