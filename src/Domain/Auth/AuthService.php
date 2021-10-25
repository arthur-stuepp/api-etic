<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Services\ApplicationService;
use App\Domain\Services\ServicePayload;
use App\Domain\User\IUserRepository;
use App\Domain\User\User;
use Firebase\JWT\JWT;

class AuthService extends ApplicationService implements IAuthService
{

    private IUserRepository $repository;
    private Authvalidation $validation;


    public function __construct(IUserRepository $repository, Authvalidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }


    public function auth(array $data): ServicePayload
    {
        $userAuth = new User($data);
        if (!$this->validation->isValid($userAuth)) {
            return $this->ServicePayload(ServicePayload::STATUS_NOT_VALID, $this->validation->getMessages());
        }
        $user = $this->repository->list($this->params(User::class)->setFilters('email', $userAuth->email)->setLimit(1))['result'][0] ?? false;
        if (!$user) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Usuario não existente']);
        }
        if (!password_verify($userAuth->password, $user->password)) {
            return $this->ServicePayload(ServicePayload::STATUS_FORBIDDEN, ['message' => 'Senha incorreta.']);
        }
        $token = $this->tokenGenerate($user);
        
        return $this->ServicePayload(ServicePayload::STATUS_VALID, ['token' => $token, 'user' => $user]);
    }

    protected function tokenGenerate(User $user): string
    {

        $token = [
            'iss' => 'https://' . $_SERVER['HTTP_HOST'],
            'iat' => time(),
            'exp' => strtotime('+1 day', time()),
            'user' => $user->id,
            'type' => $user->type,

        ];

        return JWT::encode($token, KEY);
    }
}
