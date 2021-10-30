<?php

declare(strict_types=1);

namespace App\Domain\General\Validator;

use App\Domain\AbstractEntity;
use ReflectionClass;
use ReflectionProperty;

class InputValidator extends Validator
{
    private array $data;
    private ReflectionClass $reflectionClass;
    private string $className;


    /** @noinspection PhpUnhandledExceptionInspection */
    public function isValid(array $data, AbstractEntity $entity): bool
    {
        $this->className = get_class($entity);
        $this->data = $data;
        $this->reflectionClass = new ReflectionClass($entity);
        $this->messages = [];
        $this->validateRequiredFields();
        $this->validateConsts();


        return $this->validate();
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function validateRequiredFields()
    {
        $fields = $this->reflectionClass->getProperties();
        foreach ($fields as $field) {
            $fieldName = $field->name;
            $rp = new ReflectionProperty($this->className, $fieldName);

            if ($fieldName !== 'id') {
                if (!isset($this->data[$fieldName]) && (!$rp->getType()->allowsNull())) {
                    $this->messages[$fieldName] = Validator::FIELD_REQUIRED;
                }
            }

        }
    }

    private function validateConsts(): void
    {
        $consts = $this->reflectionClass->getConstants();
        if ($consts !== []) {
            $fields = [];
            foreach ($consts as $const => $value) {
                $fields[explode('_', $const)[0]][] = $value;

            }
            foreach ($fields as $field => $constValues) {
                $property = strtolower($field);
                if (!in_array($this->data[$property], $constValues)) {
                    $this->messages[$property] = self::FIELD_INVALID;
                }
            }
        }
    }

}