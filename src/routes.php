<?php

use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthAdminMiddleware;
use App\Controllers\ObjectController;
use App\Controllers\MapController;
use App\Controllers\ToolsController;
use App\Controllers\UsersController;

return function (App $app) {
    $app->group('/api', function () use ($app) {
        // Получить список объектов
        $app->get('/objects', ObjectController::class . ':index');
        // Показать одиночный объект
        $app->get('/objects/{id:[0-9]+}', ObjectController::class . ':show');
        // Создать новый объект
        $app->post('/objects', ObjectController::class . ':store');

        // Получить маркеры объектов в радиусе
        $app->get('/markers', MapController::class . ':getByRadius');
    })->add(new AuthMiddleware());

    $app->group('/api/admin', function () use ($app) {
        $app->get('/users', UsersController::class . ':index');
        $app->get('/users/{id:[0-9]+}', UsersController::class . ':getOne');
        $app->put('/users/{id:[0-9]+}', UsersController::class . ':update');
        
        $app->get('/objects', ObjectController::class . ':index');
        $app->get('/objects/{id:[0-9]+}', ObjectController::class . ':getOne');
        $app->put('/objects/{id:[0-9]+}', ObjectController::class . ':update');
        $app->delete('/objects/{id:[0-9]+}', ObjectController::class . ':destroy');
        
        $app->get('/fake_data', ToolsController::class . ':fakeCreate');
    })->add(new AuthAdminMiddleware());
};
