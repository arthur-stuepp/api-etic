<?php

declare(strict_types=1);

namespace App\Application\Middleware\Authorization;

use App\Application\Middleware\AbstractMiddleware;
use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class Restricted extends AbstractMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $userId = (int)$route->getArgument('user');
        if ((USER_TYPE !== User::TYPE_ADMIN) && ($userId !== USER_ID)) {
            return $this->response(['message' => 'Você não tem permissao para executar essa ação'], 403);
        }

        return $handler->handle($request);
    }
}
