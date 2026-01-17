<?php
// Controlador para dashboard v1.1

// require_once dirname(__DIR__, 1) . '/presenter/dashboard_presenter_v1_1.php';
// require_once dirname(__DIR__, 1) . '/gateway/dashboard_repository.php';
// require_once dirname(__DIR__, 2) . '/use_cases/get_dashboard_data_v1_1.php';

class CamerasController {
    public function handle() {
        $data = [ 
            'url' => [
                [ 'nombre' => 'band edge',
                  'descripcion' => 'http://localhost:5001/camera/1',
                  'index' => 0 ],
                [ 'nombre' => 'reel',
                  'descripcion' => 'http://localhost:5000/camera/1',
                  'index' => 1 ]
            ],
            'usb' => [],
            'wifi' => [],
            'img' => [] 
        ];
        return $this->present($data);
    }

    public function present($data) {
        header('Content-Type: application/json');
        return json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
