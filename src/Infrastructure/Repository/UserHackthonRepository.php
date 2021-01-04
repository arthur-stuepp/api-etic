<?php


namespace App\Infrastructure\Repository;


use App\Domain\UserHackthon\IUserHackthonRepository;
use App\Domain\UserHackthon\UserHackthon;

class UserHackthonRepository extends MysqlRepository implements IUserHackthonRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'UserHackton';
        $this->class = UserHackthon::class;
    }


    public function create(UserHackthon $userHackthon)
    {
        // TODO: Implement create() method.
    }

    public function getByUser(int $user)
    {
        // TODO: Implement getByUser() method.
    }

    public function deleteUserHackthon(int $user)
    {

    }
}