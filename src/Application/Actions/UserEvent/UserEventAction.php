<?php

declare(strict_types=1);

namespace App\Application\Actions\UserEvent;

use App\Application\Actions\Action;
use App\Domain\UserEvent\IUserEventService;

abstract class UserEventAction extends Action
{
    protected IUserEventService $service;

    public function __construct(IUserEventService $service)
    {
        $this->service = $service;
    }
}
