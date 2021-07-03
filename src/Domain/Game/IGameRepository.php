<?php

namespace App\Domain\Game;

use App\Domain\IRepository;

interface IGameRepository extends IRepository
{
    public function save(Game $event);

    public function getById(int $id):false|Game;

}