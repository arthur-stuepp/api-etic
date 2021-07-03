<?php

declare(strict_types=1);

namespace App\Domain;

use Exception;
use JsonSerializable;
use ReflectionProperty;
use App\Domain\Model\DateTimeModel;

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

    public function __toString()
    {
        return $this->id;
    }

    protected function convertProperty($key, $value)
    {
        $rp = new ReflectionProperty($this, $key);
        $type = $rp->getType()->getName();

        switch ($type) {
            case 'string':
                $this->$key = (string)$value;
                return;

            case 'int':
                $this->$key = (int)$value;
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


    public function jsonSerialize()
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
