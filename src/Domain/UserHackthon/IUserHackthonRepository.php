<?php

namespace App\Domain\UserHackthon;


interface IUserHackthonRepository
{
    public function create(UserHackthon $userHackthon);

    public function getByUser(int $user);

    public function deleteUserHackthon(int $user);


}
