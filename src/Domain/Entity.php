<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\DateTimeModel;
use Exception;
use JsonSerializable;
use ReflectionProperty;

abstract class Entity implements JsonSerializable
{

    public function __construct(array $properties)
    {
        $this->setData($properties);
    }


    public function setData(array $properties)
    {

        foreach ($properties as $key => $value) {
            if (property_exists($this, $key)) {
                $this->convertProperty($key, $value);
            }
        }
    }

    private function convertProperty($key, $value)
    {
        $rp = new ReflectionProperty($this, $key);
        $type = $rp->getType()->getName();

        switch ($type) {
            case 'string':
                $this->$key = (string)$value;
                return;

            case 'int':
                if ($value === null && ($rp->getType()->allowsNull())) {
                    $this->$key = null;
                } elseif (is_numeric($value)) {
                    $this->$key = (int)$value;
                }
                return;
            case 'bool':
                $this->$key = (bool)$value;
                return;
            case 'DateTime':
                try {
                    $this->$key = new DateTimeModel($value);
                    return;
                } catch (Exception $e) {

                    return;
                }
        }
        $name = 'App\\Domain\\' . $key . '\\' . $key;
        if (class_exists($name)) {
            if (is_int($value)) {
                $this->$key = new $name(['id' => $value]);
            }
        }
    }


    public function __toString(): string
    {
        return (string)$this->id;
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        foreach ($vars as $var => $value) {
            if (is_object($value))
                if ((count((array)$value)) == 1) {
                    if (isset($value->id)) {
                        $vars[$var] = $value->id;
                    }
                }
        }
        return $vars;
    }
}
