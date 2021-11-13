<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Infrastructure\Repository\EntityParams;

abstract class AbstractDomainService
{
    protected const INVALID = 'Registro com valores invalidos';
    protected const BAD_REQUEST = 'Requisição invalida';
    protected const DUPLICATE = 'Registro com valor duplicado';
    protected const NOT_FOUND = 'Registro não encontrado';
    protected const SAVE_ERROR = 'Erro ao salvar';


    private array $defaultMessages = [
        Payload::STATUS_NOT_FOUND => self::NOT_FOUND,
        Payload::STATUS_INVALID_ENTITY => self::INVALID,
        Payload::STATUS_DUPLICATE_ENTITY => self::DUPLICATE,
        Payload::STATUS_ERROR => self::SAVE_ERROR,
    ];

    protected function servicePayload(int $status, $result = []): Payload
    {
        if ((is_array($result)) && (!isset($result['message'])) && (isset($this->defaultMessages[$status]))) {
            $result = array_merge(['message' => $this->defaultMessages[$status]], $result);
        }
        return new Payload($status, $result);
    }

    protected function params(string $class): EntityParams
    {
        return new EntityParams($class);
    }
}
