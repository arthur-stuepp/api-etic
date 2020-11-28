<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\IUserRepository;
use App\Domain\User\User;

class UserRepository extends MysqlRepository implements IUserRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
        $this->class = User::class;
    }

    public function create(User $user)
    {
        return parent::insert($user->jsonSerialize());
    }

    /**
     * @param string $email
     * @return false|User
     */
    public function getByEmail(string $email)
    {
        return $this->getByField('email', $email);
    }

    /**
     * @param int $id
     * @return false|User
     */
    public function getById(int $id)
    {
        return $this->getByField('id', $id);
    }

    /**
     * @param string $taxId
     * @return false|User
     */
    public function getByTaxId(string $taxId)
    {
        return $this->getByField('tax_id', $taxId);
    }

    public function delete(int $id):bool
    {
        return parent::delete($id);
    }

}
