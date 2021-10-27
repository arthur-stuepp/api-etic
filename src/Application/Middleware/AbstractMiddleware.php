<?php


declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Slim\Psr7\Factory\ResponseFactory;

abstract class AbstractMiddleware implements Middleware
{

    protected function response(array $data, int $status = 200): ResponseInterface
    {
        $response = (new ResponseFactory)->createResponse($status);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
