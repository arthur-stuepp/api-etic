<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\City\City;
use App\Domain\ServiceListParams;
use App\Domain\City\ICityRepository;
use App\Domain\State\IStateRepository;

class CityRepository extends MysqlRepository implements ICityRepository
{
    public function __construct(IStateRepository $stateRepository)
    {
        parent::__construct();
        $this->table = 'cities';
        $this->class = City::class;
        $this->stateRepository = $stateRepository;
    }


    public function getById(int $id)
    {
        $city= $this->getByField('id', $id);
        $city->state=$this->stateRepository->getById($city->state->id);

        return $city;
    }


    public function list(ServiceListParams $params)
    {
        // TODO: Implement list() method.
    }
}
