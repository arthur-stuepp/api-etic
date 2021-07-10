<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Domain\Auth\IAuthService;

use App\Application\Actions\Action;

abstract class AuthAction extends Action
{

    protected IAuthService $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }
}
