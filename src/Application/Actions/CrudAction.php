<?php

declare(strict_types=1);

namespace App\Application\Actions;


use App\Domain\General\Factory\ServiceFactory;
use App\Domain\CrudServiceInterface;

abstract class CrudAction extends Action
{
    protected CrudServiceInterface $service;
    protected ServiceFactory $factory;

    public function __construct(ServiceFactory $factory)
    {
        $this->factory = $factory;
    }

    protected function setService()
    {
        $uri = $this->request->getUri()->getPath();
        $pattern = explode('/', $uri)[2];
       
        $this->service = $this->factory->getService($pattern);

    }


}
