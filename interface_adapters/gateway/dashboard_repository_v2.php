<?php
/*
Path: interface_adapters/gateway/dashboard_repository.php
*/

require_once __DIR__ . '/dashboard_repository_interface_v2.php';
require_once __DIR__ . '/../../infrastructure/Database.php';
require_once __DIR__ . '/../../entities/dashboard.php';

class DashboardRepositoryV2 implements DashboardRepositoryInterfaceV2 {
    public function getDashboardData($periodo, $label = null, $ref_time = null) {
        $ls_periodos = ['semana' => 604800, 'turno' => 28800, 'hora' => 7200];
        if (!isset($ls_periodos[$periodo])) {
            throw new InvalidArgumentException('Periodo no válido');
        }
        $db = Database::getInstance();
        $conn = $db->getConnection();
        // find last entry
        $label = ($label !== null) ? $label : "vel_upm";
        $sqlLast = "SELECT `timestamp`, `reading` FROM `readings` WHERE `label` = '{$label}' ORDER BY `timestamp` DESC LIMIT 1";
        $result = $conn->query($sqlLast);
        $res = [];
        if ($result && $result->num_rows > 0) {
            $res[] = $result->fetch_assoc();
        }
        $vel_ult = isset($res[0]['reading']) ? $res[0]['reading'] : 0;
        $unixtime = isset($res[0]['timestamp']) ? $res[0]['timestamp'] : time();
        $valorInicial = $unixtime;
        if ($ref_time !== null && $ref_time <= $valorInicial) {
            $ref_time = intval($ref_time);
        } else {
            $ref_time = $valorInicial;
        }
        $tiempo1 = $ref_time - $ls_periodos[$periodo];
        $tiempo2 = $ref_time;
        $sqlData = "SELECT `timestamp`, `reading` FROM `readings`
                     WHERE `label` = '{$label}' AND `timestamp` > {$tiempo1} AND `timestamp` <= {$tiempo2}
                     ORDER BY `timestamp` ASC";
        $resultData = $conn->query($sqlData);
        $rawdata = [];
        if ($resultData && $resultData->num_rows > 0) {
            while ($row = $resultData->fetch_assoc()) {
                $rawdata[] = $row;
            }
        }
        return new Dashboard($vel_ult, $unixtime, $rawdata);
    }
}
