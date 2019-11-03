<?php

namespace  DateCalc\Controllers;
use DateCalc\Services\CalculateDaysService;
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
        if(empty($data['formatted'])){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
        }
        $payload = CalculateDaysService::calcDaysService($data);
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
        $payload = CalculateDaysService::calcWeeksService($data);
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
        if(empty($data['formatted'])){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
        }
        $payload = CalculateDaysService::calcWeekDaysService($data);
        if ($payload == 0){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
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
        if(empty($data['formatted'])){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
        }
        $payload = CalculateDaysService::calcTimezoneService($data);
        if ($payload == 0){
            return $response->withStatus(400)->write('please enter "y, d, h, m ,s or a" in formatted');
        }
        return $response->withStatus(200)->withJson($payload);
    }
}