<?php

declare(strict_types=1);

namespace App\Domain\Service\Validator;

use App\Domain\AbstractEntity;
use ReflectionClass;
use ReflectionProperty;

class InputValidator extends Validator
{
    private array $data;
    private ReflectionClass $reflectionClass;
    private string $className;


    public function isValid(array $data, AbstractEntity $entity): bool
    {
        $this->className = get_class($entity);
        $this->data = $data;
        $this->reflectionClass = new ReflectionClass($entity);
        $this->messages = [];
        $this->validateRequiredFields();
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
                if (!isset($this->data[$fieldName]) && (!$rp->getType()->allowsNull()) && (!$rp->isPrivate())) {
                    $this->messages[$fieldName] = Validator::FIELD_REQUIRED;
                }
            }
        }
    }
}
