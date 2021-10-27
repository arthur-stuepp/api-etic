<?php

declare(strict_types=1);

namespace App\Domain\General\Validator;

use App\Domain\Entity;
use ReflectionClass;
use ReflectionProperty;

class EntityValidator extends Validator
{


    private Entity $entity;
    private ReflectionClass $reflectionClass;


    public function isValid(Entity $entity): bool
    {
        $this->entity = $entity;
        $this->reflectionClass = new ReflectionClass($this->entity);
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
            $rp = new ReflectionProperty(get_class($this->entity), $fieldName);

            if (($fieldName !== 'id') && !isset($this->entity->$fieldName) && (!$rp->getType()->allowsNull())) {
                $this->messages[$fieldName] = Validator::FIELD_REQUIRED;
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
                    $property=strtolower($field);
                if (!in_array($this->entity->$property, $constValues)) {
                    $this->messages[$property] = self::FIELD_INVALID;
                }
            }
        }
    }
    
}