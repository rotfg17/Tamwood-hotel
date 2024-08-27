<?php
class Util {
    function getDays($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        
        // Calcular la diferencia
        $interval = $start->diff($end);
        
        // Devolver los días
        return $interval->days;
    }

    function getDatesBetween($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day'); // Para incluir la fecha de fin
    
        $interval = new DateInterval('P1D'); // Intervalo de 1 día
        $datePeriod = new DatePeriod($start, $interval, $end);
    
        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('Y-m-d');
        }
    
        return $dates;
    }

    function Audit_Gen(array $server_req, bool $result, string $desc, string $origin_user = "") {
        $fname = date("Y-m-d");
        $logDir = "./Back-end/Logs";

        // Verificar si el directorio existe, si no, crear el directorio
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); // Crea la carpeta con permisos 0777
        }

        $filePath = "$logDir/$fname.txt";

        // Intentar abrir el archivo para añadir contenido
        $file = fopen($filePath, "a");
        if ($file === false) {
            throw new Exception("Failed to open log file: $filePath");
        }

        // Construir el contenido del log
        $content = date("Y-m-d H:i:s") . " - " . $server_req["REMOTE_ADDR"] . ":" . $server_req["REMOTE_PORT"];
        $content .= ($origin_user !== "") ? " $origin_user " : " ";
        $content .= ($result) ? "Success" : "Failed";
        $content .= " $desc\n";

        // Escribir en el archivo
        fwrite($file, $content);
        fclose($file);
    }
}
?>