<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\ApplicationService;
use App\Domain\City\ICityRepository;
use App\Domain\School\ISchoolRepository;
use App\Domain\ServiceListParams;
use App\Domain\ServicePayload;
use Firebase\JWT\JWT;


class UserService extends ApplicationService implements IUserService
{
    private UserValidation $validation;

    private IUserRepository $repository;

    private ISchoolRepository $schoolRepository;

    private ICityRepository $cityRepository;

    public function __construct(UserValidation $validation, IUserRepository $repository, ISchoolRepository $schoolRepository, ICityRepository $cityRepository)
    {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->schoolRepository = $schoolRepository;
        $this->cityRepository = $cityRepository;
    }

    public function create(array $data): ServicePayload
    {
        $user = new User($data);
        if (!$this->validation->isValid($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        if (!$this->schoolRepository->getById($user->school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['school' => 'Escola não encontrada']);
        }
        if ($this->repository->getByEmail($user->email)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['email' => 'Email já cadastrado']);
        }
        if ($this->repository->getByTaxId($user->taxId)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['taxId' => 'CPF já cadastrado']);
        }
        if (!$this->cityRepository->getById($user->id)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['city' => 'Cidade não encontrada']);
        }
        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $this->repository->create($user)]);
    }


    public function update(int $id, array $data): ServicePayload
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Usuário não encontrado']);
        }
        $user->setData($data);

        if (!$this->validation->isValid($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        if (!$this->schoolRepository->getById($user->school)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['school' => 'Escola não existente']);
        }
        if ($userM = $this->repository->getByEmail($user->email) and $userM->id != $id) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['email' => 'Email já cadastrado']);
        }
        if ($userT = $this->repository->getByTaxId($user->taxId) and $userT->id != $id) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, ['taxId' => 'CPF já cadastrado']);
        }


        return $this->ServicePayload(ServicePayload::STATUS_CREATED, ['id' => $user->id]);
    }

    public function read(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            return $this->ServicePayload(ServicePayload::STATUS_FOUND, $this->repository->getById($id));
        }
        return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Usuário não encontrado']);
    }

    public function delete(int $id): ServicePayload
    {
        if ($this->repository->getById($id)) {
            if ($this->repository->delete($id)) {
                return $this->ServicePayload(ServicePayload::STATUS_DELETED, ['user' => 'Deletado com sucesso']);
            } else {
                return $this->ServicePayload(ServicePayload::STATUS_NOT_DELETED, ['user' => 'Registro não pode ser deletado']);
            }
        } else {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_FOUND, ['user' => 'Registro não encontrado']);
        }
    }

    public function list(ServiceListParams $params): ServicePayload
    {
        return $this->ServicePayload(ServicePayload::STATUS_FOUND, ['users' => 'users']);
    }

    public function auth($data)
    {

        $user = new User($data);
        if (!$this->validation->forAuth($user)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        //        $user = $this->partyRepository->list($data['login']);

        if (!$user || empty($user->password)) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Este login não está liberado para uso.']);
        }
        // else if (!password_verify($data['password'], $user->password)) {
        //            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Senha incorreta.']);
        //        }
        $token = ' $this->tokenGenerate($user->id)';

        return $this->ServicePayload(ServicePayload::STATUS_VALID, [
            'token' => $token,
            'tenant' => 'tenant',
            'user' => $user,
        ]);
    }

    protected function tokenGenerate(int $userId): string
    {
        $privatekey = file_get_contents(getenv('PATHTOSSLPRIVATEKEY'));
        $token = [
            'iss' => 'https://' . $_SERVER['HTTP_HOST'],
            'iat' => time(),
            'exp' => strtotime('+1 day', time()),
            'uid' => $userId,
        ];

        return JWT::encode($token, $privatekey, 'RS256');
    }
}
