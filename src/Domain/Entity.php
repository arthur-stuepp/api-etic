<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\State\State;
use DateTime;
use Exception;
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
                return;

            case 'int':
                $this->$key = (int)$value;
                return;
            case 'DateTime':
                try {
                    $this->$key = new DateTime($value);
                    return;
                } catch (Exception $e) {
                    return;
                }
        }
        $name = 'App\\Domain\\' . $key . '\\' . $key;
        if (class_exists($name)) {

            $this->$key = new $name(['id' => $value]);
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
