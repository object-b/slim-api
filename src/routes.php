<?php

use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthAdminMiddleware;
use App\Controllers\ObjectController;
use App\Controllers\MapController;
use App\Controllers\AdminController;
use App\Controllers\UsersController;

return function (App $app) {
    $app->group('/api', function () use ($app) {
        // Получить список объектов
        $app->get('/objects', ObjectController::class . ':getAll');

        // Показать одиночный объект
        $app->get('/objects/{id:[0-9]+}', ObjectController::class . ':getOne');

        // Создать новый объект
        $app->post('/objects', ObjectController::class . ':store');

        // Удалить одиночный объект
        $app->delete('/objects/{id:[0-9]+}', ObjectController::class . ':destroy');

        // Получить маркеры объектов в радиусе
        $app->get('/markers', MapController::class . ':getByRadius');
    })->add(new AuthMiddleware());

    $app->group('/api/admin', function () use ($app) {
        $app->get('/users', UsersController::class . ':getAll');
        $app->get('/objects', ObjectController::class . ':getAll');
        $app->get('/objects/{id:[0-9]+}', ObjectController::class . ':getOne');
        $app->delete('/objects/{id:[0-9]+}', ObjectController::class . ':destroy');
        $app->get('/fake_data', AdminController::class . ':fakeCreate');
    })->add(new AuthAdminMiddleware());
};
