<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Exception;

class Document implements ValueObjectInterface
{
    private string $document;

    /**
     * @throws Exception
     */
    public function __construct(string $document)
    {
        $document = preg_replace('/[^0-9]/is', '', $document);
        if (strlen($document) != 11) {
            throw new Exception('CPF invalido');
        }

        if (preg_match('/(\d)\1{10}/', $document)) {
            throw new Exception('CPF invalido');
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $document[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($document[$c] != $d) {
                throw new Exception('CPF invalido');
            }
        }
        $this->document = $document;
    }

    public function __toString()
    {
        return $this->document;
    }

    public function jsonSerialize(): string
    {
        return $this->getFormattedDocument();
    }

    public function getFormattedDocument(): string
    {
        return substr($this->document, 0, 3) . '.' .
            substr($this->document, 3, 3) . '.' .
            substr($this->document, 6, 3) . '-' .
            substr($this->document, 9, 2);
    }
}
