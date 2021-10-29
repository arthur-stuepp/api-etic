<?php

namespace App\Domain\General\Interfaces;

use JsonSerializable;

interface IEntity extends JsonSerializable
{
    public function getId(): int;

    public function setData(array $properties);

    public function __toString(): string;

    public function toRepository(): array;

}