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

    /**Sanitizes all input parameters
     * @param $data
     * @return mixed
     */
    private function sanatizeInputs($data){
        if (isset($data['start']) && !empty($data['start'])){
            $data['start'] = filter_var($data['start'], FILTER_SANITIZE_STRING);
        }
        if (isset($data['end']) && !empty($data['end'])){
            $data['end'] = filter_var($data['end'], FILTER_SANITIZE_STRING);
        }
        if (isset($data['formatted']) && !empty($data['formatted'])){
            $data['formatted'] = filter_var($data['formatted'], FILTER_SANITIZE_STRING);
        }
        if(isset($data['timezone_start']) && !empty($data['formatted'])){
            $data['timezone_start'] = filter_var($data['timezone_start'], FILTER_SANITIZE_STRING);
        }
        if(isset($data['timezone_end']) && !empty($data['formatted'])){
            $data['timezone_end'] = filter_var($data['timezone_start'], FILTER_SANITIZE_STRING);
        }
        return $data;
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

       $data = $this->sanatizeInputs($data);

        if ($this->validateDate($data['start']) == false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for start date");
        }else if($this->validateDate($data['end']) ==false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for end date");
        }

        $response = $next($request, $response);
        return $response;

    }


}