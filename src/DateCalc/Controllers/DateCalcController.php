<?php

namespace  DateCalc\Controllers;
use DateTime;
use DateTimeZone;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DateCalcController
{
    private function intervalBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = $start->diff($end);
        return $interval;
    }

    private function formatPayload($days){
        $payload = [
            'Years' => $days->format('%y'),
            'Days' => $days->format('%d'),
            'Hours' => $days->format('%h'),
            'Minutes' => $days->format('%i'),
            'Seconds' => $days->format('%s'),
        ];

        return $payload;
    }

    private function weekDayCalc($starttime, $endtime){
        $days = [];
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

    private function convertDayToSec($weekDay){
        $year = 0;
        $day = $weekDay / (24 * 3600);
        if ($day > 365){
            $year = $day / 365;
            $day = $day - (365*floor($year));

        }
        $weekDay = $weekDay % (24 * 3600);
        $hour = $weekDay / 3600;
        $weekDay %= 3600;
        $minute = $weekDay / 60;
        $weekDay %= 60;
        $second = $weekDay;

        $args = [
            'Years' => floor($year),
            'Days' => floor($day),
            'Hours' => floor($hour),
            'Minutes' => floor($minute),
            'Seconds' => floor($second)
        ];

        return $args;
    }

    public function calcDays(Request $request, Response $response){
        $data = $request->getParsedBody();
        $days = $this->intervalBetweenDates($data['start'],$data['end']);
        if ($data['formatted'] == 1){
            $payload = $this->formatPayload($days);
        }else{
            $payload = [
                'Days' => $days->days,
            ];
        }



        return $response->withStatus(200)->withJson($payload);
    }

    public function calcWeeks(Request $request, Response $response){
        $data = $request->getParsedBody();
        $days = $this->intervalBetweenDates($data['start'],$data['end']);
        if ($data['formatted'] == 1){
            $payload = $this->formatPayload($days);
        }else
        $payload = [
            'Weeks' => $days->days/7,
        ];


        return $response->withStatus(200)->withJson($payload);
    }

    public function calcWeekDay(Request $request, Response $response){
        $data = $request->getParsedBody();

        $weekDay = $this->weekDayCalc($data['start'],$data['end']);


        if($data['formatted'] == 1){
            $payload =  $this->convertDayToSec($weekDay);
        }else{
            $payload = [
                'Days' => floor($weekDay/(24 * 3600)),
            ];
        }


        return $response->withStatus(200)->withJson($payload);
    }

    public function calcTimezone(Request $request, Response $response){

        $data = $request->getParsedBody();
        $start = $data['start'];
        $end = $data['end'];

        if (isset($data['timezone_start']) && !empty($data['timezone_start'])){
            $tzoneStart = $data['timezone_start'];
        }else{
            $tzoneStart = 'Australia/Adelaide';
        }
        if (isset($data['timezone_end']) && !empty($data['timezone_end'])){
            $tzoneEnd = $data['timezone_end'];
        }else{
            $tzoneEnd = 'Australia/Adelaide';
        }

        try{
            $start = new DateTime("$start", new DateTimeZone($tzoneStart));
        } catch (Exception $e) {
            $e = $e->getMessage();
            return $response->withStatus(400)->write($e . ' Please use format e.g. "Australia/Adelaide" refer to https://www.php.net/manual/en/timezones.php');
        }

        try{
            $end = new DateTime("$end", new DateTimeZone($tzoneEnd));
        } catch (Exception $e) {
            $e = $e->getMessage();
            return $response->withStatus(400)->write($e . ' Please use format e.g. "Australia/Adelaide" refer to https://www.php.net/manual/en/timezones.php');
        }


        $days = $start->diff($end);

        if ($data['formatted'] == 1){
            $payload = $this->formatPayload($days);
        }else{
            $payload = [
                'Days' => $days->days,
            ];
        }

        return $response->withStatus(200)->withJson($payload);

    }

}