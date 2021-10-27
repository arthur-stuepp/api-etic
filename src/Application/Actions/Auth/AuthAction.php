<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Domain\General\Interfaces\IAuthService;

abstract class AuthAction extends Action
{

    protected IAuthService $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }
}
