<?php

declare(strict_types=1);

namespace App\Application\Actions\State;

use App\Domain\State\State;
use App\Domain\Services\ServiceListParams;;
use Psr\Http\Message\ResponseInterface as Response;

class StateListAction extends StateAction
{
    protected function action(): Response
    {
        $params = new ServiceListParams(State::class, $this->request->getQueryParams());
        return $this->respondWithPayload($this->service->list($params));
    }
}
