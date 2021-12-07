<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\DomainFieldException;
use App\Domain\ValueObject\DateAndTime;
use App\Domain\ValueObject\ValueObjectInterface;
use Exception;
use JsonSerializable;
use ReflectionProperty;

abstract class AbstractEntity implements JsonSerializable
{
    protected int $id;
    protected ?DateAndTime $createdAt;

    /**
     * @throws Exception
     */
    public function __construct(array $properties = [])
    {
        $this->setData($properties);
    }

    /**
     * @throws Exception
     */
    protected function setData(array $properties)
    {
        foreach ($properties as $key => $value) {
            if (property_exists($this, $key)) {
                $this->convertProperty($key, $value);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function convertProperty(string $key, $value)
    {
        $rp = new ReflectionProperty($this, $key);
        $rn = $rp->getType();
        $type = $rn->getName();
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
            default:
                if (class_exists($type)) {
                    if (in_array(AbstractEntity::class, class_parents($type))) {
                        $this->$key = new $type(['id' => $value]);
                        return;
                    }
                }
                if (in_array(ValueObjectInterface::class, class_implements($type))) {
                    try {
                        $this->$key = new $type($value);
                        return;
                    } catch (Exception $e) {
                        throw new DomainFieldException($e->getMessage(), $key);
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
        }
        $this->$key = $convertedValue;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
