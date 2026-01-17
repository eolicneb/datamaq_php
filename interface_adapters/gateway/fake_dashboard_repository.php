<?php
/*
Path: interface_adapters/gateway/dashboard_repository.php
*/

require_once __DIR__ . '/dashboard_repository_interface.php';
require_once __DIR__ . '/../../infrastructure/Database.php';

class DashboardRepository implements DashboardRepositoryInterface {
    public function getDashboardData($periodo, $conta = null) {
        $ls_periodos = ['semana' => 604800, 'turno' => 28800, 'hora' => 7200];
        if (!isset($ls_periodos[$periodo])) {
            throw new InvalidArgumentException('Periodo no válido');
        }
        file_put_contents('php://stderr', print_r(DB_SERVER."\n".DB_USERNAME."\n".DB_PASSWORD."\n".DB_NAME."\n", TRUE));

        $db = Database::getInstance();
        $conn = $db->getConnection();
        $sqlLast = "SELECT `timestamp`, `reading` FROM `readings` WHERE `label`='vel_upm' ORDER BY `id` DESC LIMIT 1";
        $result = $conn->query($sqlLast);
        $res = [];
        if ($result && $result->num_rows > 0) {
            $res[] = $result->fetch_assoc();
        }

        $vel_ult = isset($res[0]['reading']) ? $res[0]['reading'] : 0;
        $unixtime = isset($res[0]['timestamp']) ? $res[0]['timestamp'] : time();

        $valorInicial = $unixtime * 1000;
        if ($conta !== null && $conta <= $valorInicial) {
            $conta = intval($conta);
        } else {
            $conta = $valorInicial;
        }
        $tiempo1 = ($conta / 1000) - $ls_periodos[$periodo] - 80 * 60;
        $tiempo2 = $conta / 1000;
        $sqlData = "SELECT `timestamp`, `reading` FROM `readings`
                    WHERE timestamp > {$tiempo1} AND timestamp <= {$tiempo2} ORDER BY `id` ASC";
        $resultData = $conn->query($sqlData);
        $rawdata = [];

        if ($resultData && $resultData->num_rows > 0) {
            while ($row = $resultData->fetch_assoc()) {
                $rawdata[] = $row;
            }
        }
        
        return [
            'vel_ult'   => $vel_ult,
            'unixtime'  => $unixtime,
            'rawdata'   => $rawdata,
        ];
    }

    // Implementación real para v1.1: ejemplo accediendo a tabla 'produccion'
    public function getRealDashboardData($params = []) {
        $data = [];
        return [
            'produccion_bolsas_aux' => $data
        ];
    }
}
