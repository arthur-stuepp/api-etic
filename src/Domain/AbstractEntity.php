<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\DomainException;
use App\Domain\Exception\DomainFieldException;
use App\Domain\Factory\EntityFactory;
use App\Domain\ValueObject\DateTimeObject;
use Exception;
use JsonSerializable;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

abstract class AbstractEntity implements JsonSerializable
{
    protected int $id;
    protected ?DateTimeObject $createdAt;

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
     * @throws ReflectionException
     * @throws Exception
     */
    private function convertProperty(string $key, $value)
    {
        $rp = new ReflectionProperty($this, $key);
        $rn = $rp->getType();
        if (!$rn instanceof ReflectionNamedType) {
            throw new DomainException('Erro ao instanciar classe', 500);
        }
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
            case 'DateTime':
            case DateTimeObject::class:
                $convertedValue = new DateTimeObject($value);
                break;
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

    /**
     * @param int $id
     * @throws DomainFieldException
     * @throws Exception
     */
    public function setId(int $id): void
    {
        if ($id < 0) {
            throw new DomainFieldException('Id não pode ser menor que 0', 'id');
        }
        if (isset($this->id)) {
            throw new DomainFieldException('Id não pode alterado', 'id');
        }
        $this->id = $id;
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
