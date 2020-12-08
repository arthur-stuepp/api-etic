<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ServiceListParams;
use App\Domain\State\IStateRepository;
use App\Domain\State\State;

class StateRepository extends MysqlRepository implements IStateRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'states';
        $this->class = State::class;
    }


    public function getById(int $id)
    {
        return $this->getByField('id', $id);
    }


    public function list(ServiceListParams $params)
    {
        // TODO: Implement list() method.
    }
}
