<?php
/*
Path: interface_adapters/presenter/dashboard_presenter_v2.php
Presentador para la versión v2 del dashboard
*/

class DashboardPresenterV2 {
    private $title_wrappers = [
        'vel_upm' => ['Velocidad ', ' unidades por minuto'],
        'edge_position' => ['Posición de la banda: ', ' píxeles'],
        'buttler_diameter' => ['Diámetro de bobina: ', ' píxeles'],
        'buttler_width' => ['Ancho de bobina: ', ' píxeles'] 
    ];
    
    public function present($dashboard) {
        $title_parts = $this->title_wrappers[$dashboard->label];
        $response = [
            "meta" => [
                "title" => $title_parts[0] . $this->format_value($dashboard->velUlt, "int") . $title_parts[1],
                "date" => date('Y-m-d', $dashboard->unixtime),
                "turno" => $dashboard->turno
            ],
            "series" => $dashboard->rawdata,
            "features" => [],
            "producto" => $dashboard->producto,
            "velocidad" => $dashboard->velUlt,
            "formato" => "{$dashboard->formato['ancho']}x{$dashboard->formato['largo']}x{$dashboard->formato['alto']}",
            "anchoBobina" => $dashboard->formato['ancho'],
            "debug_params" => [],
            "label" => $dashboard->label
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function format_value($value, $format) {
        if ($format === "int") {
            $result = number_format($value, 0);
        } else {
            $result = number_format($value, 2, ",");
        }
        return $result;
    }
}
