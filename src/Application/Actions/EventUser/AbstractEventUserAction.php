<?php

declare(strict_types=1);

namespace App\Application\Actions\EventUser;

use App\Application\Actions\Action;
use App\Domain\Event\User\EventUserServiceInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractEventUserAction extends Action
{

    protected EventUserServiceInterface $service;

    public function __construct(EventUserServiceInterface $service, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->service = $service;
    }
}
