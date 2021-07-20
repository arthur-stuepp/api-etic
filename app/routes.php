<?php

declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\City\CityListAction;
use App\Application\Actions\City\CityReadAction;
use App\Application\Actions\User\UserReadAction;
use App\Application\Actions\Event\EventListAction;
use App\Application\Actions\Event\EventReadAction;
use App\Application\Actions\State\StateListAction;
use App\Application\Actions\State\StateReadAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\UserDeleteAction;
use App\Application\Actions\User\UserUpdateAction;
use App\Application\Middleware\Authentication\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Event\EventCreateAction;
use App\Application\Actions\Event\EventDeleteAction;
use App\Application\Actions\Event\EventUpdateAction;
use App\Application\Actions\School\SchoolListAction;
use App\Application\Actions\School\SchoolReadAction;
use App\Application\Actions\School\SchoolCreateAction;
use App\Application\Actions\School\SchoolDeleteAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\UserEvent\UserEventListAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\UserEvent\UserEventCreateAction;
use App\Application\Actions\UserEvent\UserEventDeleteAction;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
    $app->post('/auth', LoginAction::class);
    $app->post('/users', UserCreateAction::class);
    $app->group('/users', function (Group $group) {

        $group->group('/{user}', function (Group $user) {
            $user->get('', UserReadAction::class);
            $user->put('', UserUpdateAction::class);
            $user->delete('', UserDeleteAction::class);
            $user->group('/events', function (Group $events) {
                $events->get('', UserEventListAction::class);
                $events->post('/{event}', UserEventCreateAction::class);
                $events->delete('/{event}', UserEventDeleteAction::class);
            });
        });
    })->add(Auth::class);
    $app->group('/events', function (Group $group) {

        $group->get('', EventListAction::class);
        $group->post('', EventCreateAction::class);
        $group->group('/{event}', function (Group $user) {
            $user->get('', EventReadAction::class);
            $user->put('', EventUpdateAction::class);
            $user->delete('', EventDeleteAction::class);
            $user->get('/users', UserEventListAction::class);
        });
    })->add(Auth::class);
    $app->group('/schools', function (Group $group) {
        $group->get('', SchoolListAction::class);
        $group->post('', SchoolCreateAction::class);
        $group->group('/{id}', function (Group $school) {
            $school->get('', SchoolReadAction::class);
            $school->delete('', SchoolDeleteAction::class)->add(Auth::class);
        });
    });

    $app->get('/cities/{id}', CityReadAction::class);
    $app->get('/cities', CityListAction::class);
    $app->get('/states/{id}', StateReadAction::class);
    $app->get('/states', StateListAction::class);
};
