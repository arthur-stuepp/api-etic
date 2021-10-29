<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Address\IAddressRepository;
use App\Domain\General\Interfaces\UniquiPropertiesInterface;
use App\Domain\General\ServiceListParams;
use App\Domain\School\SchoolRepositoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\User;

class UserRepository implements UserRepositoryInterface
{
    private MysqlRepository $repository;
    private IAddressRepository $addressRepository;
    private SchoolRepositoryInterface $schoolRepository;

    public function __construct(MysqlRepository $mysqlRepository, IAddressRepository $addressRepository, SchoolRepositoryInterface $schoolRepository)
    {
        $this->repository = $mysqlRepository;
        $this->addressRepository = $addressRepository;
        $this->schoolRepository = $schoolRepository;
    }

    public function save(User $user): bool
    {
        return $this->repository->saveEntity($user);
    }

    public function getById(int $id)
    {
        $params = new ServiceListParams(User::class);
        $params->setFilters('id', (string)$id)
            ->setLimit(1);
        return $this->repository->list($params)['result'][0] ?? false;
    }

    public function getByEmail(string $email)
    {
        $params = new ServiceListParams(User::class);
        $params->setFilters('email', $email)
            ->setLimit(1);
        return $this->repository->list($params)['result'][0] ?? false;
    }

    public function list(ServiceListParams $params): array
    {
        $payload = $this->repository->list($params);
        $fields = $params->getFields();
        $payload['result'] = array_map(
            function (User $user) use ($fields) {
                if ($fields === [] || in_array('city', $fields)) {
                    $city = $this->addressRepository->getCityById($user->getCity()->getId());
                    $user->setCity($city);
                }
                if ($fields === [] || in_array('school', $fields)) {
                    $school = $this->schoolRepository->getById($user->getSchool()->getId());
                    $user->setSchool($school);
                }

                return $user;

            }, $payload['result']);
        return $payload;
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id, User::class);
    }


    public function getDuplicateField(UniquiPropertiesInterface $properties): ?string
    {
        return $this->repository->isDuplicateEntity($properties);
    }

    public function getError(): string
    {
        return $this->repository->getError();
    }
}

