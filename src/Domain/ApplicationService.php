<?php

namespace App\Domain;


abstract class ApplicationService {

    protected function ServicePayload(string $status, array $result = []): ServicePayload {
        return new ServicePayload($status, $result);
    }

}