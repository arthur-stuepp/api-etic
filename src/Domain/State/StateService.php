<?php


declare(strict_types=1);

namespace App\Domain\State;

use App\Domain\ApplicationService;
use App\Domain\ServiceListParams;
use App\Domain\ServicePayload;


class StateService extends ApplicationService implements IStateService
{

    private IStateRepository $repository;

    public function __construct(IStateRepository $repository)
    {

        $this->repository = $repository;
    }


    public function read(int $id): ServicePayload
    {

        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['state' => $this->repository->getById($id)]);
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['state' => 'Estado não encontrada']);
    }


    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['states' => 'states']);
    }


}
