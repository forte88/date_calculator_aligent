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

$app->post('/days', DateCalcController::class . ':calcDays');

$app->post('/weeks', DateCalcController::class . ':calcWeeks');

$app->post('/weekdays', DateCalcController::class . ':calcWeekDay');

$app->post('/timezone', DateCalcController::class . ':calcTimezone');

// Run app
$app->run();