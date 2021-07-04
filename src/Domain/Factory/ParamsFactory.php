<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\ServiceListParams;


class ParamsFactory
{
    public static function getParams(string $class): ServiceListParams
    {
        return new ServiceListParams($class);
    }

    public static function User(): ServiceListParams
    {
        return new ServiceListParams(USER::class);
    }
}
