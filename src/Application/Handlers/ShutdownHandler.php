<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class ShutdownHandler
{
    private Request $request;
    private HttpErrorHandler $errorHandler;
    private bool $displayErrorDetails;

    /**
     * ShutdownHandler constructor.
     *
     * @param Request $request
     * @param HttpErrorHandler $errorHandler
     * @param bool $displayErrorDetails
     */
    public function __construct(
        Request $request,
        HttpErrorHandler $errorHandler,
        bool $displayErrorDetails
    ) {
        $this->request = $request;
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke()
    {
        $error = error_get_last();
        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'Erro ao processar sua solicitação. Por favor, tente novamente mais tarde.';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = "ERRO FATAL: $errorMessage. ";
                        $message .= " na linha $errorLine no arquivo $errorFile.";
                        break;

                    case E_USER_WARNING:
                        $message = "WARNING: $errorMessage";
                        break;

                    case E_USER_NOTICE:
                        $message = "NOTICE: $errorMessage";
                        break;

                    default:
                        $message = "ERRO FATAL : $errorMessage";
                        $message .= " na linha $errorLine no arquivo $errorFile.";
                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke(
                $this->request,
                $exception,
                $this->displayErrorDetails,
                true,
                true
            );

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}
