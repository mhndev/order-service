<?php
// Routes


//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});


$app->get('/order','mhndev\orderService\http\OrderController:index');
$app->get('/order/{id}','mhndev\orderService\http\OrderController:show');
$app->post('/order','mhndev\orderService\http\OrderController:create');
$app->patch('/order/{id}','mhndev\orderService\http\OrderController:changeStatus');
$app->put('/order/{id}','mhndev\orderService\http\OrderController:update');
$app->delete('/order/{id}','mhndev\orderService\http\OrderController:delete');
