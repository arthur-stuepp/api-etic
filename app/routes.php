<?php

declare(strict_types=1);

use App\Application\Actions\Event\EventCreateAction;
use App\Application\Actions\Event\EventDeleteAction;
use App\Application\Actions\Event\EventReadAction;
use App\Application\Actions\School\SchoolCreateAction;
use App\Application\Actions\User\UserDeleteAction;
use App\Application\Actions\User\UserReadAction;
use Slim\App;
use App\Application\Actions\User\UserCreateAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });


    $app->group('/schools', function (Group $group) {
        $group->post('', SchoolCreateAction::class);
    });


    $app->group('/users', function (Group $group) {
        $group->post('', UserCreateAction::class);
        $group->group('/{id}', function (Group $user) {
            $user->get('', UserReadAction::class);
            $user->delete('', UserDeleteAction::class);

        });
    });
    $app->group('/events', function (Group $group) {
        $group->post('', EventCreateAction::class);
        $group->group('/{id}', function (Group $user) {
            $user->get('', EventReadAction::class);
            $user->delete('', EventDeleteAction::class);

        });
    });
};
