<?php

declare(strict_types=1);

use App\Application\Actions\Address\CityGetAction;
use App\Application\Actions\Address\StateGetAction;
use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\DeleteAction;
use App\Application\Actions\EventUser\SaveEventUserAction;
use App\Application\Actions\GetAction;
use App\Application\Actions\SaveAction;
use App\Application\Middleware\Authentication\Auth;
use App\Application\Middleware\Authorization\OnlyAdmin;
use App\Application\Middleware\Authorization\Restricted;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post('/auth', LoginAction::class);
    $app->post('/users', SaveAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', GetAction::class)->add(OnlyAdmin::class);
        $group->group('/{user}', function (Group $user) {
            $user->get('', GetAction::class)->add(Restricted::class);
            $user->put('', SaveAction::class)->add(Restricted::class);
            $user->delete('', DeleteAction::class)->add(OnlyAdmin::class);
        });
    })->add(Auth::class);


    $app->group('/events', function (Group $events) {
        $events->get('', GetAction::class);
        $events->post('', SaveAction::class)->add(OnlyAdmin::class);
        $events->group('/{event}', function (Group $event) {
            $event->get('', GetAction::class);
            $event->put('', SaveAction::class)->add(OnlyAdmin::class);
            $event->delete('', DeleteAction::class)->add(OnlyAdmin::class);
            $event->group('/users/{user}', function (Group $users) {
                $users->post('', SaveEventUserAction::class)->add(Restricted::class);
            });
        });
    })->add(Auth::class);

    $app->group('/schools', function (Group $group) {
        $group->get('', GetAction::class);
        $group->post('', SaveAction::class);
        $group->group('/{id}', function (Group $school) {
            $school->get('', GetAction::class);
            $school->put('', SaveAction::class)->add(OnlyAdmin::class)->add(Auth::class);
            $school->delete('', DeleteAction::class)->add(OnlyAdmin::class)->add(Auth::class);
        });
    });

    $app->group('/states', function (Group $states) {
        $states->get('', StateGetAction::class);
        $states->group('/{state}', function (Group $state) {
            $state->get('', StateGetAction::class);
            $state->group('/cities', function (Group $cities) {
                $cities->get('', CityGetAction::class);
                $cities->get('/{city}', CityGetAction::class);
            });
        });
    });

};
