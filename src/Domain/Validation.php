<?php

declare(strict_types=1);


namespace App\Domain;

use App\Domain\User\User;

abstract class Validation
{

    public const FIELD_NOT_SEND = 'Campo obrigatorio não enviado';
    public const FIELD_DUPLICATE = 'Campo duplicado';
    public const FIELD_INVALID = 'Valor do campo invalido';

    public const ENTITY_INVALID = 'Entidade invalida';
    public const ENTITY_DUPLICATE = 'Entidade duplicada';
    public const ENTITY_NOT_FOUND = 'Entidade não encontrada';
    public const ENTITY_SAVE_ERROR = 'Erro ao salvar entidade';

    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }

    public static function validateTaxId(string $taxId)
    {

        $taxId = self::extractNumbers($taxId);
        if (strlen($taxId) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $taxId)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $taxId[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($taxId[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public static function extractNumbers(string $string): string
    {
        return  preg_replace('/[^0-9]/is', '', $string);
    }

    public function isDuplicateEntity(Entity $entity, array $listPayload): bool
    {

        if ($listPayload['total'] > 0) {

            if (!isset($entity->id)) {
                return true;
            }
            if ($listPayload['result'][0]->id !== $entity->id) {
                return true;
            }
        }
        return false;
    }

    public function onlyAdmin(): bool
    {
        if (!defined('USER_TYPE') || (USER_TYPE !== User::TYPE_ADMIN)) {
            $this->messages['message'] = 'Você não tem permissão para executar essa ação';
        }
        return $this->validate();
    }
}
