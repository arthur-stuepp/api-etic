<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Service\CrudServiceInterface;
use App\Domain\Factory\ServiceFactory;
use Psr\Log\LoggerInterface;

abstract class CrudAction extends Action
{
    protected CrudServiceInterface $service;
    protected ServiceFactory $factory;


    public function __construct(ServiceFactory $factory, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->factory = $factory;
    }

    protected function setService()
    {
        $uri = $this->request->getUri()->getPath();
        $pattern = explode('/', $uri)[2];

        $this->service = $this->factory->getService($pattern);
    }
}
