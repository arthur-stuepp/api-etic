<?php

declare(strict_types=1);

namespace App\Application\Actions\EventUser;

use App\Application\Actions\Action;
use App\Domain\Event\EventUserServiceInterface;

abstract class AbstractEventUserAction extends Action
{

    protected EventUserServiceInterface $service;

    public function __construct(EventUserServiceInterface $service)
    {
        $this->service = $service;
    }
}