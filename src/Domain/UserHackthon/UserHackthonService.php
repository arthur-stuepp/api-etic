<?php


declare(strict_types=1);

namespace App\Domain\UserHackthon;

use App\Domain\ApplicationService;
use App\Domain\ServiceListParams;
use App\Domain\ServicePayload;
use App\Domain\User\IUserRepository;


class UserHackthonService extends ApplicationService
{

    private IUserRepository $userRepository;

    private IUserHackthonRepository $repository;

    private HackthonValidation $validation;

    public function __construct(HackthonValidation $validation, IUserHackthonRepository $repository, IUserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function create(array $data): ServicePayload
    {
        $userHackthon = new UserHackthon($data);
        if (!$this->validation->isValid($userHackthon)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        if (!$this->userRepository->getById($userHackthon->user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Usuário não encontrado']);
        }
        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->create($userHackthon)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $user = $this->repository->getByUser($id);

        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Usuário não encontrado']);
        }
        $user->setData($data);


        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $user->id]);
    }

    public function read(int $user): ServicePayload
    {
        if ($this->repository->getByUser($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->getByUser($user));
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Usuário não encontrado']);
    }

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getByUser($id)) {
            if ($this->repository->deleteUserHackthon($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['userhackthon' => 'Deletado com sucesso']);
            } else {
                return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['userhackthon' => 'Registro não pode ser deletado']);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['userhackthon' => 'Registro não encontrado']);
        }
    }

    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['users' => 'users']);
    }

}
