<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

// Define app routes
$app->get('/hello/{name}', function (Request $request,Response $response, array $args) {
    return $response->write("Hello " . $args['name']);
});

$app->post('/days', function (Request$request, Response $response){
    $data = $request->getParams();
    $date_data = [];
    $date_data['start'] = filter_var($data['start'], FILTER_SANITIZE_STRING);
    $date_data['end'] = filter_var($data['end'], FILTER_SANITIZE_STRING);

    function validateDate($date, $format = 'Y-m-d H:i:s'){
        $_date = DateTime::createFromFormat($format, $date);
        return $_date && $_date->format($format) == $date;
    }

    function daysBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = $start->diff($end);
        return $interval->format('%a');
    }

    $days = daysBetweenDates($date_data['start'],$date_data['end'] );
    $payload = [
        'Days' => $days,
    ];


    if (validateDate($date_data['start']) == false){
        return $response->withStatus(400)->write("please use correct date format for date1");
    }else if(validateDate($date_data['end']) ==false){
        return $response->withStatus(400)->write("please use correct date format for date1");
    }else{
        return $response->withStatus(200)->withJson($payload);
    }


});
// Run app
$app->run();