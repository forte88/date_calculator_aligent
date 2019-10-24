<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

$app = new Slim\App();

// Define app routes
$app->get('/hello/{name}', function (Request $request,Response $response, array $args) {
    return $response->write("Hello " . $args['name']);
});

// Run app
$app->run();