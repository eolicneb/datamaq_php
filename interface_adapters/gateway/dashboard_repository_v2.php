<?php
/*
Path: interface_adapters/gateway/dashboard_repository.php
*/

require_once __DIR__ . '/dashboard_repository_interface_v2.php';
require_once __DIR__ . '/../../infrastructure/Database.php';
require_once __DIR__ . '/../../entities/dashboard_v2.php';

class DashboardRepositoryV2 implements DashboardRepositoryInterfaceV2 {
    public function getDashboardData($periodo, $label = null, $ref_time = null) {
        error_log("Fetching data for label " . $label);
        $ls_inicios = ['completo' => 0, 'dia' => 21600, 'tarde' => 50400, 'central' => 28800, 'manana' => 21600]; 
        $ls_turnos = ['completo' => 'dia', 'dia' => '2-turnos', 'tarde' => 'turno', 'central' => 'turno', 'manana' => 'turno']; 
        $ls_span = ['semana' => 604800, 'dia' => 86400, '2-turnos' => 57600, 'turno' => 28800, 'hora' => 7200];
        if (!isset($ls_inicios[$periodo])) {
            $msg = "Periodo no válido: {$periodo}";
            throw new InvalidArgumentException($msg);
        }
        $db = Database::getInstance();
        $conn = $db->getConnection();
        // find last entry
        $label = ($label !== null) ? $label : "vel_upm";
        $final_data = $this->_getFinalDashboardValue($label, $conn);
        $vel_ult = isset($final_data['vel_ult']) ? $final_data['vel_ult'] : 0;
        $unixtime = isset($final_data['unixtime']) ? $final_data['unixtime'] : time();

        $duracion = $ls_span[$ls_turnos[$periodo]];
        $tiempo1 = $ref_time + $ls_inicios[$periodo];
        $rawdata = [
            'hoy' => ['data' => $this->_getDashboardData($tiempo1, $duracion, $label, $conn)], 
            'ayer' => ['data' => $this->_getDashboardData($tiempo1 - $ls_span['dia'], $duracion, $label, $conn)], 
            'semana_anterior' => ['data' => $this->_getDashboardData($tiempo1 - $ls_span['semana'], $duracion, $label, $conn)]
        ];
        $formato = ['ancho' => 260, 'alto' => 120, 'largo' => 80];
        $bobina = 125;
        return new DashboardV2(
            "Velocidad unidades por minuto", "Bolsa...", $periodo,
            $formato, $bobina,
            $vel_ult, $unixtime, $rawdata, $label
        );
    }
    
    private function _getFinalDashboardValue($label, $conn) {
        // find last entry
        $sqlLast = "SELECT `timestamp`, `reading` FROM `readings` WHERE `label` = '{$label}' ORDER BY `timestamp` DESC LIMIT 1";
        $result = $conn->query($sqlLast);
        $res = [];
        if ($result && $result->num_rows > 0) {
            $res[] = $result->fetch_assoc();
        }
        $vel_ult = isset($res[0]['reading']) ? $res[0]['reading'] : 0;
        $unixtime = isset($res[0]['timestamp']) ? $res[0]['timestamp'] : time();
        $dt = new DateTime(); $dt->setTimestamp($unixtime);
        return ['vel_ult' => $vel_ult, 'unixtime' => $unixtime, 'datetime' => $dt];
    }
    
    private function _getDashboardData($tiempo1, $duracion, $label, $conn) {
        $tiempo2 = $duracion + $tiempo1;
        $p = (int)($duracion / 288);
        $query = "SELECT `period` as `timestamp`, avg(`reading`) as `reading` 
                    FROM (SELECT `reading`, (`timestamp` DIV $p) * $p as `period` FROM `readings`
                           WHERE `label` = '{$label}' AND `timestamp` >= {$tiempo1} AND `timestamp` < {$tiempo2}
                           ORDER BY `timestamp` ASC) a
                   GROUP BY `period`;";
        $resultData = $conn->query($query);
        $timed_data = [];
        error_log("Brought " . $resultData->num_rows . " results for period (" . $tiempo1 . ", " . $tiempo2 . ")");
        if ($resultData && $resultData->num_rows > 0) {
            while ($row = $resultData->fetch_assoc()) {
                $timed_data[$row['timestamp']] = (float)$row['reading'];
            }
        }
        $data = [];
        $t0 = (int)($tiempo1 / $p) * $p;
        $last_value = 0;
        for ($i = 0; $i < 288; $i++) {
            $t = $t0 + $p * $i;
            // $last_value = isset($timed_data[$t]) ? $timed_data[$t] : $last_value;
            $last_value = isset($timed_data[$t]) ? $timed_data[$t] : 0;
            array_push($data, $last_value);
        }
        return $data;
    }
}
