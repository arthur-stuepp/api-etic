<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\User;
use App\Domain\ServiceListParams;
use App\Domain\User\IUserRepository;

class UserRepository extends MysqlRepository implements IUserRepository
{

    /**
     * @return false|User
     */
    public function getByEmail(string $email)
    {
        $params = new ServiceListParams(User::class, []);
        $params->setFilters('email', $email)->setLimit(1);
        
        return $this->list($params)[0] ?? false;
    }


    /**
     * @return false|User
     */
    public function getByTaxId(string $taxId)
    {
        $params = new ServiceListParams(User::class, []);
        $params->setFilters('taxId', $taxId)->setLimit(1);

        return $this->list($params)[0] ?? false;
    }

    protected function getClass(): string
    {
        return User::class;
    }
}
