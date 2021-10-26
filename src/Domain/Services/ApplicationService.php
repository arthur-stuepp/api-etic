<?php

declare(strict_types=1);

namespace App\Domain\Services;

abstract class ApplicationService
{
    public const ENTITY_INVALID = 'Dados invalidos';
    public const ENTITY_DUPLICATE = 'Registro duplicado';
    public const ENTITY_NOT_FOUND = 'Registro não encontrada';
    public const ENTITY_SAVE_ERROR = 'Erro ao salvar';

    protected function ServicePayload(int $status, $result = []): ServicePayload
    {
        return new ServicePayload($status, $result);
    }

    protected function params(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }
}
