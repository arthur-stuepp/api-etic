<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Services\ServicePayload;

interface IAuthService
{
    public function auth(array $data): ServicePayload;
}
