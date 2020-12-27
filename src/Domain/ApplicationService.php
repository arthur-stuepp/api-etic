<?php

namespace App\Domain;


abstract class ApplicationService
{

    /**
     * @param string $status
     * @param array|string|Entity $result
     * @return ServicePayload
     */
    protected function ServicePayload(int $status, $result = []): ServicePayload
    {
        return new ServicePayload($status, $result);
    }

}