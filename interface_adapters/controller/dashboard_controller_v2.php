<?php
// Controller para la versión v1 del dashboard
require_once __DIR__ . '/../../use_cases/get_dashboard_data_v2.php';
require_once __DIR__ . '/../presenter/dashboard_presenter_v2.php';
require_once __DIR__ . '/../gateway/dashboard_repository_v2.php';
require_once __DIR__ . '/../gateway/dashboard_repository_interface_v2.php';

class DashboardControllerV2 {
    protected $repository;

    public function __construct() {
        $this->repository = new DashboardRepositoryV2();
    }

    public function getDashboardData($fecha, $turno, $label = null) {
        $useCase = new GetDashboardDataV2($this->repository);
        $dashboard = $useCase->execute($fecha, $turno, $label);
        $presenter = new DashboardPresenterV2();
        return $presenter->present($dashboard);
    }
}
