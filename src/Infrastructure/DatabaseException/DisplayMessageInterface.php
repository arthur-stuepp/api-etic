<?php

namespace App\Infrastructure\DatabaseException;

interface DisplayMessageInterface
{
    public function getDisplayMessage(): string;
}
