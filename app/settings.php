<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => DEBUG,
                'logError' => true,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => 'etic-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../Files/logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
