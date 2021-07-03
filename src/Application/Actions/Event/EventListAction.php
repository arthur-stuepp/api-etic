<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use App\Domain\ServiceListParams;
use Psr\Http\Message\ResponseInterface as Response;

class EventListAction extends EventAction
{
    protected function action(): Response
    {
        $params = new ServiceListParams(Event::class, $this->request->getQueryParams());
        
        return $this->respondWithPayload($this->service->list($params));
    }
}
