<?php

namespace App\Domain\General\Interfaces;

interface  IHasUniquiProperties extends IEntity
{
    public function getFields(): array;

}