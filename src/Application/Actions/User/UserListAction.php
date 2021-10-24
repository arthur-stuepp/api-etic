<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\Services\ServiceListParams;;
use Psr\Http\Message\ResponseInterface as Response;

class UserListAction extends UserAction
{
    protected function action(): Response
    {
        $params = new ServiceListParams(User::class, $this->request->getQueryParams());

        return $this->respondWithPayload($this->service->list($params));
    }
}
