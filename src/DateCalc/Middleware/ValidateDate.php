<?php

namespace DateCalc\Middleware;


use DateTime;

/**
 * Class ValidateDate
 * @package DateCalc\Middleware
 */
class ValidateDate
{
    /**Validate datetime inputs to format 'Y-m-s H:i;s'
     * @param $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'Y-m-d H:i:s'){
        $_date = DateTime::createFromFormat($format, $date);
        return $_date && $_date->format($format) == $date;
    }

    /**Middleware function to validate and sanitize input parameters
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function __invoke($request, $response, $next)
    {

        $data = $request->getParsedBody();

        if (!isset($data) || empty($data)){
            return $response->withStatus(400)->write("Empty request");
        }

        $data['start'] = filter_var($data['start'], FILTER_SANITIZE_STRING);
        $data['end'] = filter_var($data['end'], FILTER_SANITIZE_STRING);
        $data['formatted'] = filter_var($data['formatted'], FILTER_SANITIZE_NUMBER_INT);
        if(isset($data['timezone_start'])){
            $data['timezone_start'] = filter_var($data['timezone_start'], FILTER_SANITIZE_STRING);
        }
        if(isset($data['timezone_end'])){
            $data['timezone_end'] = filter_var($data['timezone_start'], FILTER_SANITIZE_STRING);
        }


        if ($this->validateDate($data['start']) == false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for start date");
        }else if($this->validateDate($data['end']) ==false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for end date");
        }

        $response = $next($request, $response);
        return $response;

    }


}