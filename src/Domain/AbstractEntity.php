<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\General\Factory\EntityFactory;
use App\Domain\General\Model\DateTimeModel;
use Exception;
use JsonSerializable;
use ReflectionProperty;

abstract class AbstractEntity implements JsonSerializable
{
    protected int $id;
    protected ?DateTimeModel $createdAt;

    public function __construct(array $properties = [])
    {
        $this->setData($properties);
    }

    protected function setData(array $properties)
    {
        foreach ($properties as $key => $value) {
            if (property_exists($this, $key)) {
                $this->convertProperty($key, $value);
            }
        }
    }
    public function __unset(string $name)
    {
       unset($this->$name);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function convertProperty(string $key, $value)
    {

        $rp = new ReflectionProperty($this, $key);
        $type = $rp->getType()->getName();
        $convertedValue = $value;
        switch ($type) {
            case 'string':
                $convertedValue = (string)$value;
                break;

            case 'int':
                if (is_numeric($value)) {
                    $convertedValue = (int)$value;
                }
                break;
            case 'bool':
                $convertedValue = (bool)$value;
                break;
            case 'DateTime':
            case DateTimeModel::class:
                try {
                    $convertedValue = new DateTimeModel($value);
                    break;
                } catch (Exception $e) {

                    break;
                }
            default:
                if (EntityFactory::entityExist($key)) {
                    if (is_int($value)) {
                        $convertedValue = EntityFactory::getEntity($key, ['id' => $value]);
                    }
                }
        }

        if ($convertedValue === null && ($rp->getType()->allowsNull())) {
            $this->$key = null;
            return;
        }
        if (method_exists($this, 'set' . ucfirst($key))) {
            $method = 'set' . ucfirst($key);
            $this->$method($convertedValue);
            return;
        }
        $this->$key = $convertedValue;

    }
    
    public function getId(): int
    {
        return $this->id ?? 0;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
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
