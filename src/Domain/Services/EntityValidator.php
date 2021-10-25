<?php

declare(strict_types=1);

namespace App\Domain\Services;

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
        return $this->validate();
    }
    

    private function validateConst(string $field): void
    {
        if (isset($this->entity->$field)) {

            $consts = $this->reflectionClass->getConstants();
            $find = false;
            foreach ($consts as $const => $value) {
                if (strpos($const, strtoupper($field) . '_') !== false) {
                    if ($this->entity->$field === $value) {
                        $find = true;
                        break;
                    }
                }
            }
            if (!$find) {
                $this->messages[$field] = self::FIELD_INVALID;
            }
        }
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function validateRequiredFields()
    {
        $fields = $this->reflectionClass->getProperties();
        foreach ($fields as $field) {
            $fieldName=$field->name;
            $rp = new ReflectionProperty(get_class($this->entity), $fieldName);
           
            if (($fieldName!=='id')&&!isset($this->entity->$fieldName) && (!$rp->getType()->allowsNull())) {
                $this->messages[$fieldName] = Validator::FIELD_REQUIRED;
            }

        }
    }


}