<?php

declare(strict_types=1);

namespace App\Domain\Address;

use App\Domain\AbstractEntity;

class City extends AbstractEntity
{

    protected int $id;

    protected string $name;

    protected State $state;


    public function getName(): string
    {
        return $this->name;
    }

 
    public function getState(): State
    {
        return $this->state;
    }
    
    
}
