<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // App Service Providers
    $container->register(new \App\Services\EloquentServiceProvider());

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
};
