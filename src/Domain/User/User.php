<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractEntity;
use App\Domain\Address\City;
use App\Domain\General\Model\DateTimeModel;
use App\Domain\School\School;

class User extends AbstractEntity
{
    public const TYPE_ADMIN = 1;
    public const TYPE_USER = 2;

    protected int $id;
    protected int $type;
    protected string $name;
    protected string $address;
    protected City $city;
    protected string $email;
    protected DateTimeModel $birthday;
    protected ?string $company;
    protected School $school;
    protected bool $disability = false;
    protected string $password;
    protected string $taxId;
    protected ?int $indication;


    public function getCity(): City
    {
        return $this->city;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getIndication(): ?int
    {
        return $this->indication;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function comparePassword(string $passoword): bool
    {
        return password_verify($passoword, $this->password);
    }

    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        unset($json['password']);
        if (isset($json['email'])) {
            $json['email'] = substr($json['email'], 0, 3)
                . '****' .
                substr($json['email'], strpos($json['email'], "@"));
        }
        return $json;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_ARGON2I);
    }
}
