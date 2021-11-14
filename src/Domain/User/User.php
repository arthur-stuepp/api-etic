<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractEntity;
use App\Domain\Address\City;
use App\Domain\School\School;
use App\Domain\ValueObject\DateAndTime;

class User extends AbstractEntity
{
    public const TYPE_ADMIN = 1;
    public const TYPE_USER = 2;

    protected int $id = self::TYPE_USER;
    protected int $type;
    protected string $name;
    protected string $address;
    protected City $city;
    protected string $email;
    protected DateAndTime $birthday;
    protected ?string $company;
    protected School $school;
    protected bool $disability = false;
    protected string $password;
    protected string $taxId;
    protected ?int $indication;

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
}
