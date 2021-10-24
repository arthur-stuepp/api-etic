<?php

declare(strict_types=1);

namespace App\Application\Actions\School;


use App\Domain\School\School;
use App\Domain\Services\ServiceListParams;;
use Psr\Http\Message\ResponseInterface as Response;

class SchoolListAction extends SchoolAction
{
    protected function action(): Response
    {
        return $this->respondWithPayload($this->service->list(new ServiceListParams(School::class, $this->request->getQueryParams())));
    }
}
