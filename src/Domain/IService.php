<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\ServicePayload;

interface IService
{
    public function Create(array $data):ServicePayload;

    public function update(int $id, array $data);
    
    public function Delete(int $id);
    
    // public function getById(int $id);
    
    // public function getAll();
}
