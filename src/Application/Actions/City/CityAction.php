<?php


namespace App\Application\Actions\City;


use App\Application\Actions\Action;
use App\Domain\City\ICityService;

abstract class CityAction extends Action
{
    /**
     * @var ICityService
     */
    protected ICityService $service;

    public function __construct(ICityService $service)
    {
        $this->service = $service;
    }
}