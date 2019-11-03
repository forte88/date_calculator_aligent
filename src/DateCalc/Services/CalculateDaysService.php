<?php
namespace DateCalc\Services;

use DateTime;
use DateTimeZone;
use Exception;

class CalculateDaysService
{

    /**Converts start and end stings into datetime objects
     *Calculates the difference in unixtime seconds
     * @param $start
     * @param $end
     * @return int as seconds
     * @throws Exception
     */
    private function intervalBetweenDates($start, $end){
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $interval = $end->getTimestamp() - $start->getTimestamp();
        return $interval;
    }

    /**Calculates weekdays between two times in seconds
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

        /*
         * Find the difference between days and weekdays & convert the difference to seconds
         */
        $weekDayDiff = ($dayCount - $weekDayCount) * (24*60*60);
        /*
         * Will find the difference between end and start time in seconds
         * Will subtract that difference by the weekDayDiff
         * This will return an accurate timestamp to the second.
         */
        $weekDay =  (strtotime($endtime)-strtotime($starttime))-$weekDayDiff;

        return $weekDay;
    }

    /**Converts seconds into desired format in whole numbers rounded down.
     * All formatted in Years, Days, Hours, Minutes and Seconds
     * @param $timeInSeconds
     * @param $format
     * @return array
     */
    private function dateConverter($timeInSeconds, $format){
        $format = strtolower($format);
        if ($format == 'd'){
            $payload = ['days' => floor($timeInSeconds / (24 *3600))];
        }else if($format == 'y'){
            $payload = ['years'=> floor($timeInSeconds / ((24 *3600) * 365))];
        }else if($format == 'h'){
            $payload = ['hours' => floor($timeInSeconds / 3600)];
        }else if($format == 'm'){
            $payload = ['minutes' => floor($timeInSeconds / 60)];
        }else if ($format == 's'){
            $payload = ['seconds' => $timeInSeconds];
        }else if ($format == 'a'){
            $years=0;
            $days = $timeInSeconds / (24 * 3600);
            if($days > 365){
                $years =  $days / 365;
                $days = $days % 365;
            }
            $remainder = $timeInSeconds % (24 * 3600);
            $hour = $remainder / 3600;
            $remainder %= 3600;
            $minute = $remainder / 60;
            $remainder %= 60;
            $second = $remainder;

            $payload = [
                'Years' => floor($years),
                'Days' => floor($days),
                'Hours' => floor($hour),
                'Minutes' => floor($minute),
                'Seconds' => floor($second)
            ];

        }else{
            $payload = 0;
        }
        return $payload;
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function calcDaysService($data){
        $days = $this->intervalBetweenDates($data['start'],$data['end']);
        $payload = $this->dateConverter($days, $data['formatted']);
        return $payload;
    }

    /**Calculates weeks between two date parameters
     * @param $data
     * @return array
     * @throws Exception
     */
    public  function calcWeeksService($data){
        $days = $this->intervalBetweenDates($data['start'],$data['end']);
        $weeks = floor(($days / (24 *3600))/7);
        return $payload = ['weeks' => $weeks];
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function calcWeekDaysService($data){
        $weekDay = $this->weekDayCalc($data['start'],$data['end']);
        $payload = $this->dateConverter($weekDay, $data['formatted']);
        return $payload;
    }

    public function calcTimezoneService($data){

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
            return $e;
        }

        try{
            $end = new DateTime("$end", new DateTimeZone($tzoneEnd));
        } catch (Exception $e) {
            $e = $e->getMessage();
            return $e;
        }

        $days = $end->getTimestamp() - $start->getTimestamp();
        $payload = $this->dateConverter($days, $data['formatted']);
        return $payload;
    }
}