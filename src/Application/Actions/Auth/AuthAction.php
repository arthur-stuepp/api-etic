<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Domain\User\AuthServiceInterface;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{

    protected AuthServiceInterface $service;

    public function __construct(AuthServiceInterface $service, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->service = $service;
    }
}
