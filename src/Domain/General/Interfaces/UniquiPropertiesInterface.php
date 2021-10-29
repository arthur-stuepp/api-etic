<?php

namespace App\Domain\General\Interfaces;

interface  UniquiPropertiesInterface extends EntityInterface
{
    public function getProperties(): array;

}