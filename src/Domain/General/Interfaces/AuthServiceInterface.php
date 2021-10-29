<?php

declare(strict_types=1);

namespace App\Domain\General\Interfaces;

use App\Domain\ServicePayload;

interface AuthServiceInterface
{
    public function auth(array $data): ServicePayload;

}
