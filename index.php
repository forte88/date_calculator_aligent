<?php

use DateCalc\Middleware\ValidateDate;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

$config['displayErrorDetail'] = true;
$config['addContentLengthHeader'] = false;
$app = new Slim\App(['settings' => $config]);
$app->add(new ValidateDate());

// Define app routes
$app->get('/hello/{name}', function (Request $request,Response $response, array $args) {
    return $response->write("Hello " . $args['name']);
});

$app->post('/days', function (Request $request, Response $response){
    $data = $request->getParsedBody();


    function daysBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = $start->diff($end);
        return $interval->format('%a');
    }

    $days = daysBetweenDates($data['start'],$data['end'] );
    $payload = [
        'Days' => $days,
    ];


    return $response->withStatus(200)->withJson($payload);

});

$app->post('/weeks', function (Request $request, Response $response){
    $data = $request->getParsedBody();

    function daysBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = floor($start->diff($end)->days/7);
        return $interval;
    }

    $weeks = daysBetweenDates($data['start'],$data['end'] );
    $payload = [
        'Weeks' => $weeks,
    ];

    return $response->withStatus(200)->withJson($payload);

});

$app->post('/weekdays', function (Request $request, Response $response){
    $data = $request->getParsedBody();


    function weekDayCalc($starttime, $endtime){
        $days =[];
        $dayCount = 0;
        $weekDayCount = 0;

        $start = new DateTime("$starttime");
        $end = new DateTime("$endtime");

        for ($i = $start; $i <= $end; $i->modify('+1 day')){
            $days[] = $i->format('N');
            $dayCount++;
        }
        foreach ($days as $day){
            if (!($day == 6 || $day == 7)){
                $weekDayCount++;
            }
        }

        $weekDay = ($dayCount - $weekDayCount) * (24*60*60);
        $weekDay =  (strtotime($endtime)-strtotime($starttime))-$weekDay;

        return $weekDay;
    }

    function convertSecDay($weekDay){
        $year = 0;
        $day = $weekDay / (24 * 3600);
        if ($day > 365){
            $year = $day / 365;
            $day = $day - (365*floor($year));

        }
        $weekDay = $weekDay % (24 * 3600);
        $hour = $weekDay / 3600;
        $weekDay %= 3600;
        $minutes = $weekDay / 60;
        $weekDay %= 60;
        $seconds = $weekDay;

        $args = [
            'Years' => floor($year),
            'Days' => floor($day),
            'Hours' => floor($hour),
            'Minutes' => floor($minutes),
            'Seconds' => floor($seconds)
        ];

        return $args;
    }

    $weekDay = weekDayCalc($data['start'],$data['end'] );
    $weekInDays =  convertSecDay($weekDay);


    return $response->withStatus(200)->withJson($weekInDays);


});
// Run app
$app->run();