<?php


namespace App\Application\Actions\State;


use App\Application\Actions\Action;
use App\Domain\State\IStateService;

abstract class StateAction extends Action
{
    /**
     * @var IStateService
     */
    protected IStateService $service;

    public function __construct(IStateService $service)
    {
        $this->service = $service;
    }

}