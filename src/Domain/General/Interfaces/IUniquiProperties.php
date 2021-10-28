<?php

namespace App\Domain\General\Interfaces;

interface  IUniquiProperties extends IEntity
{
    public function getProperties(): array;

}