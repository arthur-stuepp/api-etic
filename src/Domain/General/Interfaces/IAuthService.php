<?php

declare(strict_types=1);

namespace App\Domain\General\Interfaces;

use App\Domain\ServicePayload;

interface IAuthService
{
    public function auth(array $data): ServicePayload;

}