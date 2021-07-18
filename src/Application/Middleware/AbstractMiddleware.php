<?php


declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Server\MiddlewareInterface as Middleware;

abstract class AbstractMiddleware implements Middleware
{

    protected function response(array $data, int $status = 200)
    {
        $response = (new ResponseFactory)->createResponse($status);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
