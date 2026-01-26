<?php
// Caso de uso para obtener datos reales del dashboard v1.1

require_once __DIR__ . '/../interface_adapters/gateway/dashboard_repository_interface_v2.php';

class GetDashboardDataV2 {
    private $dashboardRepository;

    public function __construct(DashboardRepositoryInterfaceV2 $dashboardRepository) {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function execute($fecha, $turno) {
        // Lógica real: obtener datos desde el repositorio
        return $this->dashboardRepository->getDashboardData($turno, 'vel_upm', $fecha);
    }
}
