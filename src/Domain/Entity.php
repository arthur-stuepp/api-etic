<?php

declare(strict_types=1);

namespace App\Domain;

use JsonSerializable;
use ReflectionProperty;

abstract class Entity implements JsonSerializable
{

    protected function __construct(array $properties)
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
    protected function convertProperty($key, $value)
    {
        $rp = new ReflectionProperty($this, $key);
        $type = $rp->getType()->getName();
        switch ($type) {
            case 'string':
                $this->$key = (string)$value;
                break;
            case 'int':
                $this->$key = (int)$value;
                break;   
        }
    }


    public function jsonSerialize(){
        return get_object_vars($this);
    }
}
