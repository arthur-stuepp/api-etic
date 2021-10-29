<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnusedprotectedFieldInspection */
/** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Address\City;
use App\Domain\EntityInterface;
use App\Domain\General\Interfaces\UniquiPropertiesInterface;
use App\Domain\General\Model\DateTimeModel;
use App\Domain\School\School;

class User extends EntityInterface implements UniquiPropertiesInterface
{
    public const TYPE_ADMIN = 1;
    public const TYPE_USER = 2;

    protected int $id;

    protected int $type = self::TYPE_USER;

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

    public function comparePassword(string $passoword): bool
    {
        return password_verify($passoword, $this->password);
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city)
    {
        $this->city = $city;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function setSchool(School $school)
    {
        $this->school = $school;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getProperties(): array
    {
        return ['email' => $this->email, 'taxId' => $this->type];
    }

    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        unset($json['password']);
        if (isset($json['email'])) {
            $json['email'] = substr($json['email'], 0, 3) . '****' . substr($json['email'], strpos($json['email'], "@"));
        }
        return $json;
    }

    public function setPassword(string $passoword)
    {
        $this->password = password_hash($passoword, PASSWORD_BCRYPT);
    }

}
