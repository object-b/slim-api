<?php

use Slim\App;
use App\Controllers\ObjectController;
use App\Controllers\MapController;
use App\Middleware\AuthMiddleware;

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
        
        // Remove me
        // $app->get('/fake', MapController::class . ':fakeCreate');
    })->add(new AuthMiddleware());
};
