<?php

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\AbstractEntity;
use App\Domain\UniquiPropertiesInterface;

class School extends AbstractEntity implements UniquiPropertiesInterface
{

    protected int $id;

    protected string $name;


    public function getProperties(): array
    {
        return ['name' => $this->name];
    }

    public function getName(): string
    {
        return $this->name;
    }


}
