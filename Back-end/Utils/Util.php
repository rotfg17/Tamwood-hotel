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

    function Audit_Gen(mixed $server_req, bool $result, string $desc, string $origin_user=""){
        $fname = date("Y-m-d");
        $file = fopen("./Back-end/Logs/$fname.txt","a");
        $content = date("Y-m-d H:i:s - ").$server_req["REMOTE_ADDR"].":".$server_req["REMOTE_PORT"];
        $content .= ($origin_user!="")?" $origin_user ":" ";
        $content .= ($result)?"Success":"Failed";
        $content .= " $desc\n";
        fwrite($file,$content);
        fclose($file);
    }

}
?>