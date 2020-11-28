<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Application\Actions\Action;
use App\Domain\Event\IEventService;

abstract class EventAction extends Action
{
    protected IEventService $service;

    public function __construct(IEventService $service)
    {
        $this->service = $service;
    }
}
