<?php

declare(strict_types=1);

namespace App\Application\Middleware\Authentication;

use Exception;
use Firebase\JWT\JWT;
use App\Application\Middleware\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Auth extends AbstractMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {

        $headers =  $request->getHeaders();
        if (!isset($headers['Authorization'])) {
            return $this->response(['message' => 'Token não enviado'], 401);
        }
        try {
            $token = trim(str_replace('Bearer', '', $headers['Authorization'][0]));

            JWT::decode($token, KEY, ['HS256']);
        } catch (Exception $e) {
            return $this->response(['message' => 'Token invalido'], 401);
        }

        return $handler->handle($request);
    }
}
