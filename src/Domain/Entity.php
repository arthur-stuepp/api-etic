<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\General\Factory\EntityFactory;
use App\Domain\General\Interfaces\IEntity;
use App\Domain\General\Model\DateTimeModel;
use Exception;
use ReflectionProperty;

abstract class Entity implements IEntity
{
    protected int $id;
    protected ?DateTimeModel $createdAt;

    public function __construct(array $properties=[])
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

    /** @noinspection PhpUnhandledExceptionInspection */
    protected function convertProperty($key, $value)
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
            case DateTimeModel::class:
                try {
                    $this->$key = new DateTimeModel($value);
                    return;
                } catch (Exception $e) {

                    return;
                }
        }
        if (EntityFactory::entityExist($key)) {
            if (is_int($value)) {
                $this->$key = EntityFactory::getEntity($key, ['id' => $value]);
            }
        }
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

    public function getData(): array
    {
        return get_object_vars($this);
    }
}
