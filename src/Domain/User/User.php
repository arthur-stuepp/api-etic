<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\AbstractEntity;
use App\Domain\Address\City;
use App\Domain\General\Interfaces\UniquiPropertiesInterface;
use App\Domain\General\Model\DateTimeModel;
use App\Domain\School\School;

class User extends AbstractEntity implements UniquiPropertiesInterface 
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

    public function getCityId(): int
    {
        return $this->city->getId();
    }

    public function getSchoolId(): int
    {
        return $this->school->getId();
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function setSchool(School $school): void
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
