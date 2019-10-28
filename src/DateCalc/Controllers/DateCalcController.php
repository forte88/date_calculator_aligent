<?php

namespace  DateCalc\Controllers;
use DateTime;
use DateTimeZone;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class DateCalcController
 * @package DateCalc\Controllers
 */
class DateCalcController
{
    /**Creates date objects and  returns interval between two date times
     * @param $start
     * @param $end
     * @return bool|\DateInterval
     * @throws Exception
     */
    private function intervalBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = $start->diff($end);
        return $interval;
    }

    /**Formats date time object into an array of years days etc
     * @param $days
     * @return array
     */
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

    /**Calculates the weekdays between two datetime objects in unix time
     * @param $starttime
     * @param $endtime
     * @return false|float|int
     * @throws Exception
     */
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

    /**Converts unix time into years, days, hours etc
     * @param $weekDay
     * @return array
     */
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

    /**Calculate days between two datetime params
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws Exception
     */
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

    /**Calculates weeks between two datetime params
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws Exception
     */
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

    /**Calculates weekdays between two datetime params
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws Exception
     */
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

    /**Calculates days between two datetime params with the option to
     * change time zones of the input params
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
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