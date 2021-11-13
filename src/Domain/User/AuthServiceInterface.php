<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Service\Payload;

interface AuthServiceInterface
{
    public function auth(array $data): Payload;
}
