<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Service\Payload;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected Request $request;
    protected Response $response;
    protected array $args;
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    /**
     * @return Response
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return ?array
     * @throws HttpBadRequestException
     */
    protected function getFormData(): ?array
    {
        $data = file_get_contents('php://input');
        if (empty($data)) {
            return [];
        }
        $input = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'JSON Malformatado.');
        }

        return $input;
    }

    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        $this->addLogger($payload);
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        if (DEBUG === false && is_array($json) && isset($json['description'])) {
            unset($json['description']);
        }
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }

    private function addLogger(ActionPayload $payload): void
    {
        $path = $this->request->getUri()->getPath();
        $method = $this->request->getMethod();
        $name = $method . ' ' . $path;
        $data = [
            'loggedUser' => 'Nenhum usuario logado',
            'result' => $payload->getData()
        ];
        if (defined('USER_ID')) {
            $data['loggedUser'] = USER_ID;
        }
        if ($payload->getStatusCode() < 400) {
            if ($this->request->getMethod() === 'GET') {
                $data['result'] = 'Listagem de registros';
            }
            $this->logger->info($name, $data);
        } elseif ($payload->getStatusCode() < 500) {
            $this->logger->error($name, $data);
        } else {
            $this->logger->critical($name, $data);
        }
    }

    protected function respondWithPayload(Payload $payload): Response
    {
        $payload = new ActionPayload($payload->getStatus(), $payload->getResult());

        return $this->respond($payload);
    }
}
