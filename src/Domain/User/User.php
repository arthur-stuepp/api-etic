<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractEntity;
use App\Domain\Address\City;
use App\Domain\Exception\DomainFieldException;
use App\Domain\School\School;
use App\Domain\ValueObject\DateAndTime;
use App\Domain\ValueObject\Document;
use App\Domain\ValueObject\Email;

class User extends AbstractEntity
{
    public const TYPE_ADMIN = 1;
    public const TYPE_USER = 2;

    protected int $id;
    protected int $type = self::TYPE_USER;
    protected string $name;
    protected string $address;
    protected City $city;
    protected Email $email;
    protected DateAndTime $birthday;
    protected ?string $company;
    protected School $school;
    protected bool $disability = false;
    protected string $password;
    protected Document $document;
    protected ?int $indication;

    public function comparePassword(string $passoword): bool
    {
        return password_verify($passoword, $this->password);
    }

    /**
     * @throws DomainFieldException
     */
    private function setIndication(int $indication)
    {
        if ($indication <= 0) {
            throw new DomainFieldException('Indicação precisa ser Maior que 0 ', 'indication');
        }
        if ($this->getId() === $indication) {
            throw new DomainFieldException('Indicação não pode ser você mesmo', 'indication');
        }
    }

    /**
     * @throws DomainFieldException
     */
    private function setType(int $type)
    {
        if (!in_array($type, [self::TYPE_ADMIN, self::TYPE_USER])) {
            throw new DomainFieldException('Tipo invalido', 'type');
        }
        $this->type = $type;
    }

    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        unset($json['password']);
        
        return $json;
    }
}
