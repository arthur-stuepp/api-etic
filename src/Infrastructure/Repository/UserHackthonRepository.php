<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\User;
use App\Domain\ServiceListParams;
use App\Domain\UserHackthon\UserHackthon;
use App\Domain\UserHackthon\IUserHackthonRepository;

class UserHackthonRepository extends MysqlRepository implements IUserHackthonRepository
{

    /**
     * @return false|UserHackthon
     */
    public function getByUser(int $user)
    {
        $params = new ServiceListParams(UserHackthon::class, []);
        $params->setFilters('user',(string)$user);
        $params->setLimit(1);
        return $this->list($params)[0] ?? false;
    }

    protected function getClass(): string
    {
        return UserHackthon::class;
    }
    public function deleteUserHackthon(int $user){
        
    }

}