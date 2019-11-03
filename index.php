<?php

require 'vendor/autoload.php';
use DateCalc\Middleware\ValidateDate;
use DateCalc\Controllers\DateCalcController;
$config['displayErrorDetail'] = true;
$config['addContentLengthHeader'] = false;
$app = new Slim\App(['settings' => $config]);
$app->add(new ValidateDate());

// Define app routes

//Route to calculate days
$app->post('/days', DateCalcController::class . ':calcDays');

//Route to calculate weeks
$app->post('/weeks', DateCalcController::class . ':calcWeeks');

//Route to calculate weekdays
$app->post('/weekdays', DateCalcController::class . ':calcWeekDay');

//Route to calculate days between two datetimes with the option to input timezones
$app->post('/timezone', DateCalcController::class . ':calcTimezone');

// Run app
$app->run();