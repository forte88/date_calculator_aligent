<?php

namespace  DateCalc\Controllers;
use DateCalc\Services\CalculateDaysService as Service;
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

    /**Calculate days between two datetime params
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws Exception
     */
    public function calcDays(Request $request, Response $response){
        $data = $request->getParsedBody();
        $service = new Service();
        $payload = $service->calcDaysService($data);
        if ($payload == 0){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
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
        $service = new Service();
        $payload = $service->calcWeeksService($data);
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