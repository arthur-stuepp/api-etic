<?php

declare(strict_types=1);

namespace App\Domain\Game;

use App\Domain\ServicePayload;
use App\Domain\ApplicationService;
use App\Domain\Traits\TraitListService;
use App\Domain\Traits\TraitReadService;
use App\Domain\Traits\TraitDeleteService;


class GameService extends ApplicationService implements IGameService
{
    private EventValidation $validation;
    private IGameRepository $repository;

    use TraitDeleteService;
    use TraitReadService;
    use TraitListService;


    public function __construct(EventValidation $validation, IGameRepository $repository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
    }

    public function create(array $data): ServicePayload
    {
        $event = new Game($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }

        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->save($event)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $event = $this->repository->getById($id);

        if (!$event) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['message' => 'Registro não encontrado']);
        }
        $event->setData($data);

        if (!$this->validation->isValid($event)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }


        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $event->id]);
    }
}
