<?php

namespace DateCalc\Middleware;


use DateTime;

class ValidateDate
{
    private function validateDate($date, $format = 'Y-m-d H:i:s'){
        $_date = DateTime::createFromFormat($format, $date);
        return $_date && $_date->format($format) == $date;
    }

    public function __invoke($request, $response, $next)
    {
        $data = $request->getParsedBody();

        if (!isset($data) || empty($data)){
            return $response->withStatus(400)->write("Empty request");
        }

        $data['start'] = filter_var($data['start'], FILTER_SANITIZE_STRING);
        $data['end'] = filter_var($data['end'], FILTER_SANITIZE_STRING);
        $data['formatted'] = filter_var($data['formatted'], FILTER_SANITIZE_NUMBER_INT);


        if ($this->validateDate($data['start']) == false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for start date");
        }else if($this->validateDate($data['end']) ==false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for end date");
        }else if(!($data['formatted'] == 0 Xor $data['formatted'] == 1) || $data['formatted'] == null){
            return $response->withStatus(400)->write("please enter 0 for difference in days or 1 for for formatted results");
        }

        $response = $next($request, $response);
        return $response;

    }


}