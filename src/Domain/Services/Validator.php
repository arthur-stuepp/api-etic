<?php

declare(strict_types=1);


namespace App\Domain\Services;

abstract class Validator
{

    public const RULE_CONST = 'RULE_CONST';
    public const RULE_TAX_ID = 'RULE_TAX_ID';
    public const FIELD_REQUIRED = 'Campo obrigatorio';
    public const FIELD_INVALID = 'Valor invalido';
    public const FIELD_DUPLICATE = 'Valor duplicado';
    public const ENTITY_INVALID = 'Dados invalidos';
    public const ENTITY_DUPLICATE = 'Registro duplicado';
    public const ENTITY_NOT_FOUND = 'Registro não encontrada';
    public const ENTITY_SAVE_ERROR = 'Erro ao salvar';


    protected array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function validateTaxId(string $taxId): bool
    {

        $taxId = self::extractNumbers($taxId);
        if (strlen($taxId) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $taxId)) {
            return false;
        }
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

    public function extractNumbers(string $string): string
    {
        return preg_replace('/[^0-9]/is', '', $string);
    }

    protected function validate(): bool
    {
        return count($this->messages) == 0;
    }


}
