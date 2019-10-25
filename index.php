<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
use DateCalc\Middleware\ValidateDate;
use DateCalc\Controllers\DateCalcController;
$config['displayErrorDetail'] = true;
$config['addContentLengthHeader'] = false;
$app = new Slim\App(['settings' => $config]);
$app->add(new ValidateDate());

// Define app routes
$app->get('/hello/{name}', function (Request $request,Response $response, array $args) {
    return $response->write("Hello " . $args['name']);
});

$app->post('/days', DateCalcController::class . ':calcDays');

$app->post('/weeks', DateCalcController::class . ':calcWeeks');

$app->post('/weekdays', DateCalcController::class . ':calcWeekDay');

// Run app
$app->run();