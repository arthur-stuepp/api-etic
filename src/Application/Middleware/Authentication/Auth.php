<?php

declare(strict_types=1);

namespace App\Application\Middleware\Authentication;

use App\Application\Middleware\AbstractMiddleware;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
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
        if ($request->getMethod() !== 'OPTIONS') {
            $headers = $request->getHeaders();
            if (!isset($headers['Authorization'])) {
                return $this->response(['message' => 'Token não enviado'], 401);
            }
            $token = trim(str_replace('Bearer', '', $headers['Authorization'][0]));
            try {
                $token = JWT::decode($token, KEY, ['HS256']);
            } catch (ExpiredException $e) {
                return $this->response(['message' => 'Token Expirado'], 401);
            } catch (Exception $e) {
                return $this->response(['message' => 'Token invalido'], 401);
            }
            define('USER_ID', $token->user);
            define('USER_TYPE', $token->type);
        }


        return $handler->handle($request);
    }
}
