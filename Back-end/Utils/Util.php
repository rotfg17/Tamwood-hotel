<?php
class Util {
    function getDays($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        
        // calc
        $interval = $start->diff($end);
        
        // return days
        return $interval->days;
    }

    function getDatesBetween($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day'); // for containing end date
    
        $interval = new DateInterval('P1D'); // 1 dat interval
        $datePeriod = new DatePeriod($start, $interval, $end);
    
        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('Y-m-d');
        }
    
        return $dates;
    }
}
?>