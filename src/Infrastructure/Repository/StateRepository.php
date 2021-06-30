<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ServiceListParams;
use App\Domain\State\IStateRepository;
use App\Domain\State\State;

class StateRepository extends MysqlRepository implements IStateRepository
{
    protected function getClass(): string{
        return State::class;
    }
}
