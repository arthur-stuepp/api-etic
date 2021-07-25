<?php

declare(strict_types=1);

namespace App\Domain\UserEvent;

use App\Domain\User\User;
use App\Domain\Validation;
use App\Domain\ServiceListParams;

class UserEventValidation extends Validation
{


    public function hasPermissionToSave(int $user): bool
    {
        $this->messages = [];
        if ((USER_TYPE === User::TYPE_USER) && (USER_ID !== $user)) {
            $this->messages['message'] = 'Você não tem permissao para executar esse ação';
        }
        return $this->validate();
    }

    public function hasPermissionToRead(int $id): bool
    {
        $this->messages = [];
        if ((USER_TYPE !== User::TYPE_ADMIN) && (USER_ID !== $id)) {
            $this->messages['message'] = 'Você não tem permissao para acessar esse registro';
        }
        return $this->validate();
    }

    public function hasPermissionToReadEventsUser(int $user): bool
    {
        $this->messages = [];
        if ((USER_TYPE === User::TYPE_USER) && (USER_ID !== $user)) {
            $this->messages['message'] = 'Você não tem permissao para acessar esse registro';
        }
        return $this->validate();
    }

    public function hasPermissionToReadUsersEvent(): bool
    {
        return $this->onlyAdmin();
    }
    public function hasPermissionToDelete(int $user)
    {
        $this->messages = [];
        if ((USER_TYPE !== User::TYPE_ADMIN) && (USER_ID !== $user)) {
            $this->messages['message'] = 'Você não tem permissao para deletar esse registro';
        }
        return $this->validate();
    }
}
