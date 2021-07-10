<?php


declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class AbstractMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {

        $headers =  $request->getHeaders();
        if (!isset($headers['Authorization'])) {
        }

        return $handler->handle($request);
    }

    protected function response(array $data, int $status = 200)
    {
        $response = (new ResponseFactory)->createResponse($status);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
