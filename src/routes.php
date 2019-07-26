<?php

use Slim\App;
use App\Controllers\ApiController;

return function (App $app) {
    $container = $app->getContainer();

    // Получить все объекты
    $app->get('/objects', ApiController::class . ':index');

    // Показать одиночный объект
    $app->get('/objects/{id:[0-9]+}', function ($request, $response, $args) {

    });

    // Создать новый объект
    $app->post('/objects', function ($request, $response, $args) {

    });
};
