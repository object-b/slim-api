<?php

use Slim\App;
use App\Controllers\ObjectController;
use App\Middleware\AuthMiddleware;

return function (App $app) {
    $app->group('/api', function () use ($app) {
        // Получить все объекты
        $app->get('/objects', ObjectController::class . ':index');

        // Показать одиночный объект
        $app->get('/objects/{id:[0-9]+}', ObjectController::class . ':show');

        // Создать новый объект
        $app->post('/objects', ObjectController::class . ':store');

        // Удалить одиночный объект
        $app->delete('/objects/{id:[0-9]+}', ObjectController::class . ':destroy');
    })->add(new AuthMiddleware());
};
