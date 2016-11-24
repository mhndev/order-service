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


$container['authorizationMiddleware'] = function($c){
    return new \mhndev\orderService\middlewares\MiddlewareAuthorization($c);
};

$container['corsMiddleware'] = function($c){
    return new \Tuupola\Middleware\Cors([
        "origin" => ["*"],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
        "headers.expose" => ["Etag"],
        "credentials" => true,
        "cache" => 86400
    ]);
};


$container['corsMiddlewareMe'] = function($c){
    return new \mhndev\orderService\middlewares\MiddlewareCors($c);
};


$container['orderAccess'] = function($c){
    return new \mhndev\order\OrderAccess(include 'orders.php');
};


$container[\mhndev\orderService\http\OrderController::class] = function ($c) {
    return new \mhndev\orderService\http\OrderController($c['OrderRepository'], $c['orderAccess']);
};
