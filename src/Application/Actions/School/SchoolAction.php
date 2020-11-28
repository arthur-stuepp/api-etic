<?php


namespace App\Application\Actions\School;


use App\Application\Actions\Action;
use App\Domain\School\ISchoolService;

abstract class SchoolAction extends Action
{
    /**
     * @var ISchoolService
     */
    protected ISchoolService $service;

    public function __construct(ISchoolService $service)
    {
        $this->service = $service;
    }
}