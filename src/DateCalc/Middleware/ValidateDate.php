<?php

namespace DateCalc\Middleware;


use DateTime;

class ValidateDate
{
    function validateDate($date, $format = 'Y-m-d H:i:s'){
        $_date = DateTime::createFromFormat($format, $date);
        return $_date && $_date->format($format) == $date;
    }

    public function __invoke($request, $response, $next)
    {
        $data = $request->getParsedBody();
        $data['start'] = filter_var($data['start'], FILTER_SANITIZE_STRING);
        $data['end'] = filter_var($data['end'], FILTER_SANITIZE_STRING);

        if ($this->validateDate($data['start']) == false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for start date");
        }else if($this->validateDate($data['end']) ==false){
            return $response->withStatus(400)->write("please use correct date format [Y-m-d H:i:s] for start date");
        }

        $response = $next($request, $response);
        return $response;

    }


}