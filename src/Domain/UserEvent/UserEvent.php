<?php


declare(strict_types=1);

namespace App\Domain\UserEvent;

use App\Domain\Entity;

class UserEvent extends Entity
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public int $user;

    public int $event;


}