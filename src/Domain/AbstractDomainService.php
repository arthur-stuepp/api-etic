<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\General\ServiceListParams;

abstract class AbstractDomainService
{
    public const ENTITY_INVALID = 'Entidade invalida';
    public const ENTITY_DUPLICATE = 'Entidade duplicada';
    public const ENTITY_NOT_FOUND = 'Entidade não encontrada';
    public const ENTITY_SAVE_ERROR = 'Erro ao salvar entidade';
    

    private array $defaultMessages = [
        ServicePayload::STATUS_NOT_FOUND => self::ENTITY_NOT_FOUND,
        ServicePayload::STATUS_INVALID_ENTITY => self::ENTITY_INVALID,
        ServicePayload::STATUS_DUPLICATE_ENTITY => self::ENTITY_DUPLICATE,
        ServicePayload::STATUS_ERROR => self::ENTITY_SAVE_ERROR,
    ];

    protected function ServicePayload(int $status, $result = []): ServicePayload
    {
        if ((is_array($result)) && (!isset($result['message'])) && (isset($this->defaultMessages[$status]))) {
         $result=array_merge(['message'=> $this->defaultMessages[$status]],$result);
        }
        return new ServicePayload($status, $result);
    }

    protected function params(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }

}
