<?php /** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace App\Domain\School;

use App\Domain\Entity;
use App\Domain\General\Interfaces\IUniquiProperties;

class School extends Entity implements IUniquiProperties
{

    protected int $id;

    protected string $name;
    

    public function getProperties(): array
    {
        return ['name' => $this->name];
    }

}
