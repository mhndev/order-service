<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function($c){
    $settings = $c->get('settings')['db'];
    $mongoDriver = new \mhndev\order\MongoDriverManager();
    $mongoDriver->addClient(new MongoDB\Client($settings['driver']['master']['host'] ), 'master' );
    $mongoClient = $mongoDriver->byClient('master');
    $db = $mongoClient->selectDatabase('order');

    return $db;
};


$container['OrderRepository'] = function ($c) {
    return new mhndev\order\repositories\mongo\OrderRepository($c['db'], 'orders');
};


$container[\mhndev\orderService\http\OrderController::class] = function ($c) {
    return new \mhndev\orderService\http\OrderController($c['OrderRepository']);
};
