<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\General\ServiceListParams;

abstract class AbstractDomainService
{
    protected const INVALID = 'Registro com valores invalidos';
    protected const BAD_REQUEST = 'Requisição invalida';
    protected const DUPLICATE = 'Registro com valor duplicado';
    protected const NOT_FOUND = 'Registro não encontrado';
    protected const SAVE_ERROR = 'Erro ao salvar';


    private array $defaultMessages = [
        ServicePayload::STATUS_NOT_FOUND => self::NOT_FOUND,
        ServicePayload::STATUS_INVALID_INPUT => self::BAD_REQUEST,
        ServicePayload::STATUS_INVALID_ENTITY => self::INVALID,
        ServicePayload::STATUS_DUPLICATE_ENTITY => self::DUPLICATE,
        ServicePayload::STATUS_ERROR => self::SAVE_ERROR,
    ];

    protected function servicePayload(int $status, $result = []): ServicePayload
    {
        if ((is_array($result)) && (!isset($result['message'])) && (isset($this->defaultMessages[$status]))) {
            $result = array_merge(['message' => $this->defaultMessages[$status]], $result);
        }
        return new ServicePayload($status, $result);
    }

    protected function params(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }
}
