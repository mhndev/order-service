<?php
// Routes

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->get('/me','mhndev\orderService\http\OrderController:me');
$app->get('/{id}','mhndev\orderService\http\OrderController:show');
$app->post('/','mhndev\orderService\http\OrderController:create');
$app->patch('/{id}','mhndev\orderService\http\OrderController:changeStatus');
$app->put('/{id}','mhndev\orderService\http\OrderController:update');
$app->delete('/{id}','mhndev\orderService\http\OrderController:delete');
