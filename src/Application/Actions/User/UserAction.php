<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\IUserService;

abstract class UserAction extends Action
{
    protected IUserService $service;

    public function __construct(IUserService $service)
    {
        $this->service = $service;
    }
}
